<?php
declare(strict_types=1);

namespace ContentBlocks\Model\Behavior;

use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\Log\Log;
use Cake\ORM\Behavior;

/**
 * SortableEntities behavior
 */
class SortableEntitiesBehavior extends Behavior
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'scope_fields' => [],
    ];

    public function beforeSave(EventInterface $event, EntityInterface $entity, $options): bool
    {
        $this->updateSortOrder($entity, $options);
        return true;
    }

    private function updateSortOrder(EntityInterface $this_entity, $options): void
    {
        if (!$this_entity->isNew() && !$this_entity->isDirty('sort')) {
            return;
        }

        if (!empty($options['skip_sort_update'])) {
            return;
        }

        $conditions = [
            $this->getTable()->getAlias() . '.id !=' => $this_entity->id ?? 0,
        ];

        foreach ($this->getConfig("scope_fields") as $field) {
            $value = $this_entity->get($field);
            if ($value) {
                $conditions[$field] = $value;
            }
        }

        $entities = $this->getTable()->find()
            ->where($conditions)->toArray();


        if (empty($this_entity->sort) || (int)$this_entity->sort <= 0) {
            $this_entity->sort = count($entities) + 1;

            return;
        }

        $sort = 1;

        foreach ($entities as $entity) {

            if ($sort === $this_entity->sort) {

                $sort++;
            }

            $entity->sort = $sort;
            $sort++;
        }

        try {
            $this->getTable()->saveMany($entities, ['skip_sort_update' => true]);
        } catch (\Exception $e) {
            Log::write('debug',
                'Cannot update Sort order for ' . get_class($this_entity) . ' with ID ' . $this_entity->id);
        }
    }
}
