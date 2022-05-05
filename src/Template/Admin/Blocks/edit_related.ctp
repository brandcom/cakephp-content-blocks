<?php
/**
 * @var \Cake\ORM\Entity|\ContentBlocks\Model\Entity\Traits\BelongsToBlockTrait $relatedEntity
 * @var \ContentBlocks\Model\Entity\Block $block
 * @var \ContentBlocks\View\AppView $this
 */

use \Cake\Utility\Inflector;

$block_edit_url = [
    'plugin' => "ContentBlocks",
    'controller' => "Blocks",
    'action' => "edit",
    $block->block->id,
];

?>
<div class="actions">
    <h2>
        <?= __d("content_blocks", "Actions") ?>
    </h2>
    <ul>
        <li>
            <?= $this->Html->link(__d("content_blocks", "Go back"), $block_edit_url) ?>
        </li>
        <li>
            <?= $this->Form->postLink(__d("content_blocks", "Delete {0}", [$relatedEntity->getTitle()]), [
                'plugin' => "ContentBlocks",
                'controller' => "Blocks",
                'action' => "deleteRelated",
                $relatedEntity->id,
                $relatedEntity->getSource(),
            ], [
                'data' => [
                    'redirect' => \Cake\Routing\Router::url($block_edit_url),
                ],
                'confirm' => __d("content_blocks", "Do you want to delete {0} #{1}?", [
                    $relatedEntity->getTitle(),
                    $relatedEntity->get("id"),
                ])
            ]) ?>
        </li>
    </ul>
</div>
<?= $this->Form->create($relatedEntity, $relatedEntity->getFormOptions()) ?>
<fieldset>
    <legend>
        <?= __d("content_blocks", "Edit {0}", [
            $relatedEntity->getTitle()
        ]) ?>
    </legend>
    <?php foreach ($relatedEntity->getFields() as $field => $options): ?>
        <?= !empty($options['beforeControl']) ? $options['beforeControl'] : null ?>
        <?= $this->Form->control($field, $options) ?>
        <?= !empty($options['afterControl']) ? $options['afterControl'] : null ?>
    <?php endforeach; ?>
</fieldset>
<?= $this->Form->submit() ?>
<?= $this->Form->end() ?>
