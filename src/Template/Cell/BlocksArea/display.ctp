<?php
/**
 * @var \ContentBlocks\Model\Entity\Area $area
 * @var \ContentBlocks\View\AppView $this
 */
?>
<?php foreach ($area->blocks as $block): ?>
    <?= $this->cell("ContentBlocks.BlocksArea::renderBlock", ['block' => $block]) ?>
<?php endforeach; ?>
