<?php
namespace ContentBlocks\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Routing\Router;
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
            ->boolean('is_published');

        $validator
            ->scalar('type')
            ->maxLength('type', 255)
            ->requirePresence('type', 'create')
            ->notEmptyString('type');

        $validator
            ->scalar('html_anchor')
            ->maxLength('html_anchor', 255)
            ->allowEmptyString('html_anchor');

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
     * Pass Variables to the Admin edit template.
     *
     * Similar to BlocksTable::getViewVariables()
     *
     * @param $entity
     * @return EntityInterface[]
     * @throws \ReflectionException
     */
    public function getAdminViewVariables($entity): array
    {
        return [];
    }

    /**
     * Retrieves the owner entity of the block's area.
     *
     * @param $block_id
     * @return EntityInterface
     */
    public function getOwner(Block $block): EntityInterface
    {
        $BlockTable = $this->getTableLocator()->get("ContentBlocks.Blocks");
        if ($block->getSource() !== "ContentBlocks.Blocks") {
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

    /**
     * Returns the user-created *ContentBlock,
     * containing the Block, the BlockArea and the *ContentBlock's managed models.
     *
     * @param int $block_id
     * @return Block|EntityInterface
     */
    public function getContentBlock(int $block_id)
    {
        /**
         * @var Block $block
         * @var Block $contentBlock
         * @var BlocksTable $contentBlocksTable
         */
        $block = $this->get($block_id);
        $contentBlocksTable = $this->getTableLocator()->get($block->type);

        $contentBlock = $contentBlocksTable
            ->find()
            ->where([
                'content_blocks_block_id' => $block->id,
            ])
            ->first();

        $contained = ['Blocks.Areas'];
        $contained = array_merge($contained, $contentBlock->getManagedModels());

        return $contentBlocksTable
            ->find()
            ->where([
                'content_blocks_block_id' => $block->id,
            ])
            ->contain($contained)
            ->first();
    }

    public function getViewUrl(Block $block): ?array
    {
        if ($block->get("block")) {
            $block = $block->get("block");
        }

        $owner = $this->getOwner($block);

        if (method_exists($owner, "getContentBlocksViewUrl")) {

            return $owner->getContentBlocksViewUrl($block);
        }

        $anchor = $block->html_anchor ?? null;

        $route = [
            'prefix' => false,
            'plugin' => false,
            'controller' => $block->area->owner_model,
            'action' => 'view',
            $block->area->owner_id,
            '#' => $anchor ?: "content-block-" . $block->id,
        ];

        return Router::routeExists($route) ? $route : null;
    }
}
