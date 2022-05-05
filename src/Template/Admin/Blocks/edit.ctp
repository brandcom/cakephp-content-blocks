<?php
/**
 * @var Block|Entity $contentBlock
 * @var AppView $this
 */

use Cake\ORM\Entity;
use ContentBlocks\Model\Entity\Block;
use ContentBlocks\View\AppView;
use Cake\Routing\Router;

$entity_edit_url = [
    'plugin' => false,
    'controller' => $contentBlock->block->area->owner_model,
    'action' => "edit",
    $contentBlock->block->area->owner_id,
];

?>
<div class="actions">
    <h2>
        <?= __d("vendor/content_blocks", "Actions") ?>
    </h2>
    <ul>
        <li>
            <?= $this->Html->link(__d("vendor/content_blocks", "Go back"), $entity_edit_url) ?>
        </li>
        <li>
            <?= $this->Form->postLink(__d("vendor/content_blocks", "Delete Block"), [
                'plugin' => "ContentBlocks",
                'controller' => "Blocks",
                'action' => "delete",
                $contentBlock->id,
                $contentBlock->block->type,
            ], [
                'confirm' => __d("vendor/content_blocks", "Do you really want to delete Block #{0}?", [$contentBlock->id]),
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
        <?= __d("vendor/content_blocks", "Fields for {0}", [
            $contentBlock->getTitle(),
        ]) ?>
    </legend>
    <?= empty($contentBlock->getFields()) ? __d("vendor/content_blocks", "This Block does not have any editable fields.") : null ?>
    <?php foreach ($contentBlock->getFields() as $field => $options): ?>
        <?= !empty($options['beforeControl']) ? $options['beforeControl'] : null ?>
        <?= $this->Form->control($field, $options) ?>
        <?= !empty($options['afterControl']) ? $options['afterControl'] : null ?>
    <?php endforeach; ?>
</fieldset>
<fieldset>
    <legend>
        <?= __d("vendor/content_blocks", "General Settings") ?>
    </legend>
    <?= $this->Form->control('block.sort', [
        'label' => __d("vendor/content_blocks", "Sort Order"),
    ]) ?>
    <?= $this->Form->control('block.is_published', [
        'label' => __d("vendor/content_blocks", "Is Published?"),
    ]) ?>
    <?= $this->Form->control('block.html_anchor', [
        'label' => __d("vendor/content_blocks", "HTML Anchor"),
    ]) ?>
    <p>
        <?= $contentBlock->getViewRoute() ? $this->Html->link("&rarr; zum Block", $contentBlock->getViewRoute(), [
            'escapeTitle' => false,
            'target' => "_blank",
        ]) : __d("vendor/content_blocks", "Error: Missing route for Block") ?>
    </p>
</fieldset>
<?= $this->Form->submit() ?>
<?= $this->Form->end() ?>
<?= $this->element("ContentBlocks.Admin/Blocks/edit/related_models_loop") ?>
