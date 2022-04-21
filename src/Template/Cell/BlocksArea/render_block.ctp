<?php
/**
 * @var \ContentBlocks\View\AppView $this
 * @var \ContentBlocks\Model\Entity\Block $contentBlock
 */
?>
<div class="content-block <?= strtolower($contentBlock->block->type) ?>">
    <?= $contentBlock->render() ?>
</div>
