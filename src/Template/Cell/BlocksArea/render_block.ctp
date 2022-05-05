<?php
/**
 * @var \ContentBlocks\View\AppView $this
 * @var \ContentBlocks\Model\Entity\Block $contentBlock
 * @var array $viewVariables
 */

use \Cake\Utility\Inflector;

?>
<?= $this->Html->div(
    sprintf("content-block %s", Inflector::dasherize($contentBlock->getSource())),
    $contentBlock->render($viewVariables),
    [
        'id' => $contentBlock->block->html_anchor ?: sprintf("content-block-%s", $contentBlock->block->id),
    ]
) ?>
