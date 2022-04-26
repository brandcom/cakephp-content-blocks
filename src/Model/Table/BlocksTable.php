<?php
namespace ContentBlocks\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use ContentBlocks\Model\Entity\Block;

/**
 * Blocks Model
 *
 * @property \ContentBlocks\Model\Table\AreasTable&\Cake\ORM\Association\BelongsTo $Areas
 *
 * @method \ContentBlocks\Model\Entity\Block get($primaryKey, $options = [])
 * @method \ContentBlocks\Model\Entity\Block newEntity($data = null, array $options = [])
 * @method \ContentBlocks\Model\Entity\Block[] newEntities(array $data, array $options = [])
 * @method \ContentBlocks\Model\Entity\Block|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \ContentBlocks\Model\Entity\Block saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \ContentBlocks\Model\Entity\Block patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \ContentBlocks\Model\Entity\Block[] patchEntities($entities, array $data, array $options = [])
 * @method \ContentBlocks\Model\Entity\Block findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BlocksTable extends Table
{
    use LocatorAwareTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('content_blocks_blocks');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('ContentBlocks.SortableEntities');

        $this->belongsTo('Areas', [
            'foreignKey' => 'content_blocks_area_id',
            'joinType' => 'INNER',
            'className' => 'ContentBlocks.Areas',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->nonNegativeInteger('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->nonNegativeInteger('sort')
            ->allowEmptyString('sort');

        $validator
            ->scalar('type')
            ->maxLength('type', 255)
            ->requirePresence('type', 'create')
            ->notEmptyString('type');

        return $validator;
    }

    public function beforeFind(Event $event, Query $query): Query
    {
        if ($this->getAlias() === "Blocks") {
            return $query->orderAsc("sort");
        }

        return $query;
    }

    /**
     * Return a relational array of variables that should be
     * available in the block template/element.
     *
     * E.g., [
     *      'article' => $article,
     *      'currentWeather' => $currentWeather,
     * ]
     *
     * @param Block $entity
     * @return array
     */
    public function getViewVariables($entity): array
    {
        $owner = $this->getOwner($entity);
        return [
            "owner" => $owner,
        ];
    }

    /**
     * Retrieves the owner entity of the block's area.
     *
     * @param $block_id
     * @return EntityInterface
     * @throws \ReflectionException
     */
    public function getOwner(Block $block): EntityInterface
    {
        $BlockTable = $this->getTableLocator()->get("ContentBlocks.Blocks");
        if ($block->getSource() !== "ContentBlocks.Block") {
            $block = $BlockTable->get($block->get("content_blocks_block_id"));
        }

        $block = $BlockTable->get($block->id, [
            'contain' => [
                'Areas',
            ],
        ]);

        /**
         * @var Block $block
         */
        return $this->getTableLocator()->get($block->area->owner_model)->get($block->area->owner_id);
    }
}
