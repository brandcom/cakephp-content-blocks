<?php
/**
 * @var \ContentBlocks\Model\Entity\Area $area
 * @var \ContentBlocks\View\AppView $this
 * @var array $viewVariables
 * @var \Cake\Datasource\EntityInterface $entity
 */

use Cake\Log\Log;

?>
<?php foreach ($area->blocks as $block): ?>
    <?php
    if (!$block->isActive()) {
        Log::debug(__d('content_blocks', 'Inactive Block #{0} on Area #{1}.', [
            $block->id,
            $area->id,
        ]));

        continue;
    }
    if (!$block->canBeOnEntity($entity)) {
        Log::debug(__d('content_blocks', 'Block #{0} is not allowed on Entity #{1} of type "{2}".', [
            $block->id,
            $entity->id,
            $entity->getSource(),
        ]));

        continue;
    }
    ?>
    <?php if ($block->is_published): ?>
        <?= $this->cell("ContentBlocks.BlocksArea::renderBlock",
            ['block' => $block, 'viewVariables' => $viewVariables]) ?>
    <?php endif; ?>
<?php endforeach; ?>
