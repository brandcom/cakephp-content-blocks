<?php
/**
 * @var Block|Entity $contentBlock
 * @var AppView $this
 */

use Cake\ORM\Entity;
use ContentBlocks\Model\Entity\Block;
use ContentBlocks\View\AppView;

?>
<p>
    <?= $this->Html->link(__d("ContentBlocks", "Go back"), [
        'plugin' => false,
        'controller' => \Cake\Utility\Inflector::pluralize(str_replace("App\Model\Entity\\", '', $contentBlock->block->area->owner_model)),
        'action' => "edit",
        $contentBlock->block->area->owner_id,
    ]) ?>
</p>
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
        'label' => __d("ContentBlocks", "Sort Order")
    ]) ?>
</fieldset>
<?= $this->Form->submit() ?>
<?= $this->Form->end() ?>
