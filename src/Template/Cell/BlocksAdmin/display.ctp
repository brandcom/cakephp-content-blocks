<?php
/**
 * @var \ContentBlocks\Model\Entity\Area $area
 * @var \ContentBlocks\View\AppView $this
 * @var array $availableBlocks
 */
?>
<div class="content-blocks-admin">
    <h2>
        <?= __d("ContentBlocks", "Content Blocks for {0} #{1}", [
            h(\Cake\Utility\Inflector::singularize($area->owner_model)),
            h($area->owner_id),
        ]) ?>
    </h2>
    <table>
        <thead>
        <tr>
            <th>
                <?= __d("ContentBlocks", "Sort") ?>
            </th>
            <th>
                <?= __d("ContentBlocks", "Block Description") ?>
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
                <h3>
                    <?= __d("ContentBlocks", "Add a new block") ?>
                </h3>
                <?php if (empty($availableBlocks)): ?>
                    <p>
                        <?= __d("ContentBlocks", "There are no blocks available yet.") ?>
                    </p>
                <?php else: ?>
                    <ul>
                        <?php foreach ($availableBlocks as $block): ?>
                            <?php
                            /**
                             * @var \ContentBlocks\Model\Entity\Block $block
                             */
                            ?>
                            <li>
                                <?= $this->Form->postLink($block->getTitle(), [
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
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </td>
        </tr>
        </tfoot>
    </table>
</div>
