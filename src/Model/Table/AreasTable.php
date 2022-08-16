<?php
namespace ContentBlocks\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\Filesystem\Folder;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Table;
use Cake\Utility\Inflector;
use Cake\Validation\Validator;
use ContentBlocks\Model\Entity\Area;
use ContentBlocks\Model\Entity\Block;

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
    public const CUSTOM_KEY = 'custom_key';

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

    /**
     * @param EntityInterface|string $entityOrKey
     * @return Area
     */
    public function findOrCreateForEntityOrKey($entityOrKey): Area
    {
        if (is_string($entityOrKey)) {

            $area = $this->findOrCreate([
                'owner_model' => self::CUSTOM_KEY,
                'owner_id' => $entityOrKey,
            ]);

        } elseif (is_a($entityOrKey, EntityInterface::class)) {

            $area = $this->findOrCreate([
                'owner_model' => $entityOrKey->getSource(),
                'owner_id' => $entityOrKey->id,
            ]);

        } else {

            throw new \InvalidArgumentException(sprintf('$entityOrKey must be of type "EntityInterface" or "string", but got "%s".', get_class($entityOrKey)));
        }

        $area = $this->get($area->id, [
            'contain' => [
                'Blocks.Areas',
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

    /**
     * @param EntityInterface|string $entityOrKey
     * @return array
     */
    public function getAvailableBlocks($entityOrKey): array
    {
        $entitiesDir = new Folder(ROOT . DS . 'src' . DS . 'Model' . DS . 'Entity' . DS);
        $blockFiles = $entitiesDir->find(".*\ContentBlock.php");

        $blocks = array_map(
            function ($block) use ($entityOrKey) {

                try {

                    $reflectionClass = new \ReflectionClass("App\\Model\\Entity\\" . str_replace('.php', '', $block));
                    $blockTable = $this->getTableLocator()->get(Inflector::pluralize($reflectionClass->getShortName()));
                    $blockEntity = $blockTable->newEntity();

                    /**
                     * @var Block $blockEntity
                     */
                    if (!$blockEntity->isActive()) {

                        return false;
                    }

                    if (is_string($entityOrKey)) {

                        /**
                         * TODO add possibility to enable/disable blocks for keys
                         */
                        return $blockEntity;
                    }

                    if ($blockEntity->canBeOnEntity($entityOrKey)) {

                        return $blockEntity;
                    }

                    return false;

                } catch (\Exception $e) {

                    return false;
                }
            },
            $blockFiles
        );

        return array_filter(
            $blocks,
            function ($block) {
                return (bool)$block;
            }
        );
    }
}
