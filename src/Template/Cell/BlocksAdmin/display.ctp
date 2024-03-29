<?php
/**
 * @var \ContentBlocks\Model\Entity\Area $area
 * @var \ContentBlocks\View\AppView $this
 * @var array $availableBlocks
 */

use ContentBlocks\Model\Table\AreasTable;
?>
<div class="content-blocks-admin">
    <h2>
        <?php if ($area->owner_model === AreasTable::CUSTOM_KEY): ?>
            <?= __d("content_blocks", "Content Blocks for key {0}", [
                h($area->owner_id),
            ]) ?>
        <?php else: ?>
            <?= __d("content_blocks", "Content Blocks for {0} #{1}", [
                h(\Cake\Utility\Inflector::singularize($area->owner_model)),
                h($area->owner_id),
            ]) ?>
        <?php endif; ?>
    </h2>
    <table>
        <thead>
        <tr>
            <th>
                <?= __d("content_blocks", "Sort") ?>
            </th>
            <th>
                <?= __d("content_blocks", "Published") ?>
            </th>
            <th>
                <?= __d("content_blocks", "Block Description") ?>
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
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="4">
                <h3>
                    <?= __d("content_blocks", "Add a new block") ?>
                </h3>
                <?php if (empty($availableBlocks)): ?>
                    <p>
                        <?= __d("content_blocks", "There are no blocks available yet.") ?>
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
