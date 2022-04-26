<?php
/**
 * @var \ContentBlocks\Model\Entity\Area $area
 * @var \ContentBlocks\View\AppView $this
 * @var array $availableBlocks
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
                    <?= $this->Number->format($block->sort) ?>
                </td>
                <td>
                    <?= $block->custom_block->getDescription() ?>
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
                    <?php foreach ($availableBlocks as $block): ?>
                        <?php
                        /**
                         * @var \ContentBlocks\Model\Entity\Block $block
                         */
                        ?>
                        <?= $this->Form->postButton($block->getTitle(), [
                            'prefix' => "admin",
                            'plugin' => "ContentBlocks",
                            'controller' => "Blocks",
                            'action' => "add",
                        ], [
                            'data' => [
                                'area_id' => $area->id,
                                'type' => $block->getSource(),
                            ]
                        ]) ?>
                    <?php endforeach; ?>
                </div>
            </td>
        </tr>
        </tfoot>
    </table>
</div>
