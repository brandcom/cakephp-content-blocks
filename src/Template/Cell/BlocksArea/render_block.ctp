<?php
/**
 * @var \ContentBlocks\View\AppView $this
 * @var \ContentBlocks\Model\Entity\Block $contentBlock
 * @var array $viewVariables
 * @var \Cake\Datasource\EntityInterface $owner
 */

use \Cake\Utility\Inflector;

?>

<?php
if (!$contentBlock->isActive()) {

    debug(__d('content_blocks', 'Inactive Block #{0} of type "{1}" should not be on this Area.', [
        $contentBlock->id,
        $contentBlock->getSource(),
    ]));

} elseif (!$contentBlock->canBeOnEntity($owner)) {

    debug(__d('content_blocks', 'Block #{0} is not allowed on Entity #{1} of type "{2}".', [
        $contentBlock->id,
        $owner->id,
        $owner->getSource(),
    ]));

} else {
    echo $this->Html->div(
        sprintf("content-block %s", Inflector::dasherize($contentBlock->getSource())),
        $contentBlock->render($viewVariables),
        [
            'id' => $contentBlock->block->html_anchor ?: sprintf("content-block-%s", $contentBlock->block->id),
        ]
    );
}
?>
