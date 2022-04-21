<?php
namespace ContentBlocks\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

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
}
