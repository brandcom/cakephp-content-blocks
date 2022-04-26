<?php
namespace ContentBlocks\View\Cell;

use Cake\Datasource\EntityInterface;
use Cake\ORM\Table;
use Cake\View\Cell;
use ContentBlocks\Model\Entity\Block;
use ContentBlocks\Model\Table\AreasTable;
use ContentBlocks\Model\Table\BlocksTable;

/**
 * BlocksArea cell
 *
 * @property AreasTable $Areas
 */
class BlocksAreaCell extends Cell
{
    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array
     */
    protected $_validCellOptions = [];

    /**
     * Initialization logic run at the end of object construction.
     *
     * @return void
     */
    public function initialize()
    {
        $this->loadModel("ContentBlocks.Areas");
    }

    /**
     * Default display method.
     *
     * @return void
     */
    public function display(EntityInterface $entity)
    {
        $area = $this->Areas->findOrCreateForEntity($entity);

        $this->set(compact('area'));
    }

    public function renderBlock(Block $block)
    {
        $this->loadModel($block->type);
        /**
         * @var BlocksTable $table
         * @var Block $contentBlock
         */
        $table = $this->{$block->type};
        $contentBlock = $table->find()
            ->where([
                'content_blocks_block_id' => $block->id,
            ])
            ->contain(['Blocks'])
            ->first();

        $viewVariables = $table->getViewVariables($contentBlock);

        $this->set(compact('contentBlock', 'viewVariables'));
    }
}
