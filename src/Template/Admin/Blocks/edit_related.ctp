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
        <?= __d("ContentBlocks", "Actions") ?>
    </h2>
    <ul>
        <li>
            <?= $this->Html->link(__d("ContentBlocks", "Go back"), $block_edit_url) ?>
        </li>
    </ul>
</div>
<?= $this->Form->create($relatedEntity, $relatedEntity->getFormOptions()) ?>
<fieldset>
    <legend>
        <?= __d("ContentBlocks", "Edit {0}", [
            Inflector::humanize(Inflector::underscore((new ReflectionClass(get_class($relatedEntity)))->getShortName()))
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
