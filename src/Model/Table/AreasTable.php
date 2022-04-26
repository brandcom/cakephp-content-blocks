<?php
namespace ContentBlocks\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\ORM\Entity;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Routing\Router;
use Cake\Validation\Validator;
use ContentBlocks\Model\Entity\Area;

/**
 * ContentBlocksAreas Model
 *
 * @property \ContentBlocks\Model\Table\BlocksTable&\Cake\ORM\Association\HasMany $Blocks
 *
 * @method \ContentBlocks\Model\Entity\Area get($primaryKey, $options = [])
 * @method \ContentBlocks\Model\Entity\Area newEntity($data = null, array $options = [])
 * @method \ContentBlocks\Model\Entity\Area[] newEntities(array $data, array $options = [])
 * @method \ContentBlocks\Model\Entity\Area|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \ContentBlocks\Model\Entity\Area saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \ContentBlocks\Model\Entity\Area patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \ContentBlocks\Model\Entity\Area[] patchEntities($entities, array $data, array $options = [])
 * @method \ContentBlocks\Model\Entity\Area findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AreasTable extends Table
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

        $this->setTable('content_blocks_areas');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Blocks', [
            'foreignKey' => 'content_blocks_area_id',
            'className' => 'ContentBlocks.Blocks',
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
            ->scalar('owner_model')
            ->maxLength('owner_model', 255)
            ->requirePresence('owner_model', 'create')
            ->notEmptyString('owner_model');

        return $validator;
    }

    public function findOrCreateForEntity(EntityInterface $entity): Area
    {
        $area = $this->findOrCreate([
            'owner_model' => $entity->getSource(),
            'owner_id' => $entity->id,
        ]);

        $area = $this->get($area->id, [
            'contain' => [
                'Blocks',
            ],
        ]);

        $area->blocks = array_map(
            function ($block) {
                $block->custom_block = $this->getTableLocator()->get($block->type)->find()->where(['content_blocks_block_id' => $block->id])->first();
                return $block;
            },
            $area->blocks
        );

        return $area;
    }
}
