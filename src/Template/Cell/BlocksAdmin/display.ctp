<?php
/**
 * @var \ContentBlocks\Model\Entity\Area $area
 * @var \ContentBlocks\View\AppView $this
 */
?>
<div class="content-blocks-admin">
    <p class="content-blocks-admin__entity-info">
        <small>
            <?= __d("ContentBlocks", "Area for {0} #{1}", [
                h($area->owner_model),
                h($area->owner_id),
            ]) ?>
        </small>
    </p>
    <table>
        <thead>
        <tr>
            <th>
                <?= __d("ContentBlocks", "Sort") ?>
            </th>
            <th>
                <?= __d("ContentBlocks", "Block Type") ?>
            </th>
            <th>
                <?= __("Actions") ?>
            </th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($area->blocks as $block): ?>
            <tr>
                <td>
                    <?= h($block->sort) ?>
                </td>
                <td>
                    <?= h($block->type) ?>
                </td>
                <td>
                    <?= $this->Html->link(__("Edit"), [
                        'prefix' => 'admin',
                        'plugin' => "ContentBlocks",
                        'controller' => 'Blocks',
                        'action' => 'edit',
                        $block->id,
                    ]) ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="3">
                <p>
                    <?= __d("ContentBlocks", "Add new block") ?>
                </p>
                <div>
                    <?php foreach ($this->ContentBlocksAdmin->getBlockList() as $type): ?>
                        <?= $this->Form->postButton($type, [
                            'prefix' => "admin",
                            'plugin' => "ContentBlocks",
                            'controller' => "Blocks",
                            'action' => "add",
                        ], [
                            'data' => [
                                'area_id' => $area->id,
                                'type' => $type,
                            ]
                        ]) ?>
                    <?php endforeach; ?>
                </div>
            </td>
        </tr>
        </tfoot>
    </table>
</div>
