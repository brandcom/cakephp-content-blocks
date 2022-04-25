<?php
/**
 * @var Block|Entity $contentBlock
 * @var AppView $this
 */

use Cake\ORM\Entity;
use ContentBlocks\Model\Entity\Block;
use ContentBlocks\View\AppView;

$entity_edit_url = [
    'plugin' => false,
    'controller' => \Cake\Utility\Inflector::pluralize(str_replace("App\Model\Entity\\", '',
        $contentBlock->block->area->owner_model)),
    'action' => "edit",
    $contentBlock->block->area->owner_id,
];

?>
<div class="actions">
    <h2>
        <?= __d("ContentBlocks", "Actions") ?>
    </h2>
    <ul>
        <li>
            <?= $this->Html->link(__d("ContentBlocks", "Go back"), $entity_edit_url) ?>
        </li>
        <li>
            <?= $this->Form->postLink(__d("ContentBlocks", "Delete Block"), [
                'plugin' => "ContentBlocks",
                'controller' => "Blocks",
                'action' => "delete",
                $contentBlock->id,
                $contentBlock->block->type,
            ], [
                'confirm' => __d("ContentBlocks", "Do you really want to delete Block #{0}?", [$contentBlock->id]),
                'data' => [
                    'redirect' => \Cake\Routing\Router::url($entity_edit_url),
                ],
            ]) ?>
        </li>
    </ul>
</div>
<?= $this->Form->create($contentBlock, $contentBlock->getFormOptions()) ?>
<fieldset>
    <legend>
        <?= __d("ContentBlocks", "Fields for {0}", [
            $contentBlock->getTitle(),
        ]) ?>
    </legend>
    <?php foreach ($contentBlock->getFields() as $field => $options): ?>
        <?= !empty($options['beforeControl']) ? $options['beforeControl'] : null ?>
        <?= $this->Form->control($field, $options) ?>
        <?= !empty($options['afterControl']) ? $options['afterControl'] : null ?>
    <?php endforeach; ?>
</fieldset>
<fieldset>
    <legend>
        <?= __d("ContentBlocks", "General Settings") ?>
    </legend>
    <?= $this->Form->control('block.sort', [
        'label' => __d("ContentBlocks", "Sort Order"),
    ]) ?>
</fieldset>
<?= $this->Form->submit() ?>
<?= $this->Form->end() ?>
<?php if (!empty($contentBlock->getManagedModels())): ?>
    <div class="related-models">
        <h2>
            <?= __d("ContentBlocks", "Related Models") ?>
        </h2>
        <?php foreach ($contentBlock->getManagedModels() as $model => $title): ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
