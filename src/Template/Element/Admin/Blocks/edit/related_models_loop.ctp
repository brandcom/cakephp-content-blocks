<?php
/**
 * @var \ContentBlocks\Model\Entity\Block $contentBlock
 */

use \Cake\Utility\Inflector;

?>
<?php if (!empty($contentBlock->getManagedModels())): ?>
    <div class="related-models">
        <h2>
            <?= __d("vendor/content_blocks", "Related Models") ?>
        </h2>
        <?php foreach ($contentBlock->getManagedModels() as $model): ?>
            <?php
            $singular = Inflector::singularize($model);
            $instance = (new ReflectionClass("App\\Model\\Entity\\{$singular}"))->newInstance();
            ?>
            <h3>
                <?= $instance->getTitle() ?>
            </h3>
            <table>
                <thead>
                <tr>
                    <th>
                        <?= __d("vendor/content_blocks", "ID") ?>
                    </th>
                    <?php if ($instance->isAccessible("sort")): ?>
                        <th>
                            <?= __d("vendor/content_blocks", "Sort") ?>
                        </th>
                    <?php endif; ?>
                    <th>
                        <?= __d("vendor/content_blocks", "Description") ?>
                    </th>
                    <th>
                        <?= __d("vendor/content_blocks", "Actions") ?>
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($contentBlock->{Inflector::underscore($model)} as $entity): ?>
                    <tr>
                        <td>
                            <?= $entity->get("id") ?>
                        </td>
                        <?php if ($instance->isAccessible("sort")): ?>
                            <td>
                                <?= $entity->sort ?>
                            </td>
                        <?php endif; ?>
                        <td>
                            <?= $entity->getDescription() ?>
                        </td>
                        <td>
                            <?= $this->Html->link(__d("vendor/content_blocks", "Edit"), [
                                'action' => 'editRelated',
                                $contentBlock->id,
                                $contentBlock->block->type,
                                $model,
                                $entity->id,
                            ]) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?= $this->Form->postButton(__d("vendor/content_blocks", "Add new"), [
                'plugin' => "ContentBlocks",
                'controller' => "Blocks",
                'action' => "addRelated",
                $contentBlock->id,
                $contentBlock->block->type,
                $model,
            ]) ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
