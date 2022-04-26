<?php
/**
 * @var \ContentBlocks\View\AppView $this
 * @var \ContentBlocks\Model\Entity\Block $contentBlock
 * @var array $viewVariables
 */
?>
<div class="content-block <?= strtolower($contentBlock->block->type) ?>">
    <?= $contentBlock->render($viewVariables) ?>
</div>
