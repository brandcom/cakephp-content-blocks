<?php
/**
 * @var \ContentBlocks\Model\Entity\Area $area
 * @var \ContentBlocks\View\AppView $this
 * @var array $viewVariables
 */
?>
<?php foreach ($area->blocks as $block): ?>
    <?= $this->cell("ContentBlocks.BlocksArea::renderBlock", ['block' => $block, 'viewVariables' => $viewVariables]) ?>
<?php endforeach; ?>
