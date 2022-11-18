<?php
/**
 * @var \ContentBlocks\Model\Entity\Block $contentBlock
 */

use \Cake\Utility\Inflector;
use ContentBlocks\Model\Entity\Traits\BelongsToBlockTrait;

?>
<?php if (!empty($contentBlock->getManagedModels())): ?>
    <div class="related-models">
        <h2>
            <?= __d("content_blocks", "Related Models") ?>
        </h2>
        <?php foreach ($contentBlock->getManagedModels() as $model): ?>
            <?php
            $singular = Inflector::singularize($model);
            $instance = (new ReflectionClass("App\\Model\\Entity\\{$singular}"))->newInstance();
            if (!in_array(BelongsToBlockTrait::class, (array)class_uses($instance))) {
                throw new \ContentBlocks\Error\ContentBlocksException(
                    sprintf('%s must use the %s. See https://github.com/brandcom/cakephp-content-blocks#7-edit-related-models',
                        get_class($instance),
                        BelongsToBlockTrait::class
                    )
                );
            }
            ?>
            <h3>
                <?= $instance->getTitle() ?>
            </h3>
            <table>
                <thead>
                <tr>
                    <th>
                        <?= __d("content_blocks", "ID") ?>
                    </th>
                    <?php if ($instance->isAccessible("sort")): ?>
                        <th>
                            <?= __d("content_blocks", "Sort") ?>
                        </th>
                    <?php endif; ?>
                    <th>
                        <?= __d("content_blocks", "Description") ?>
                    </th>
                    <th>
                        <?= __d("content_blocks", "Actions") ?>
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
                            <?= $this->Html->link(__d("content_blocks", "Edit"), [
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
            <?= $this->Form->postButton(__d("content_blocks", "Add new"), [
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
