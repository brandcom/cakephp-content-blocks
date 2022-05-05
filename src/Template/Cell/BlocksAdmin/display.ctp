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
                <?= __d("ContentBlocks", "Published") ?>
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
            <tr class="block-table-row <?= $block->is_published ? "--is-published" : "--not-published" ?>">
                <td>
                    <?= $this->Number->format($block->sort) ?>
                </td>
                <td>
                    <?= $block->is_published ? $this->Html->div("published-mark text-success success", "&check;") : $this->Html->div("published-mark text-danger error", "&cross;") ?>
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
                    <?= $block->is_published && $block->getViewRoute() ? $this->Html->link(__("View"), $block->getViewRoute()) : null ?>
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
