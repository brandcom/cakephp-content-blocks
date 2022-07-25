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
 * @property BlocksTable $Blocks
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
        $this->loadModel("ContentBlocks.Blocks");
    }

    /**
     * Default display method.
     *
     * @return void
     */
    public function display(EntityInterface $entity, array $viewVariables=[])
    {
        $area = $this->Areas->findOrCreateForEntity($entity);

        $this->set(compact('area', 'entity', 'viewVariables'));
    }

    public function renderBlock(Block $block, array $viewVariables)
    {
        /**
         * @var BlocksTable $table
         * @var Block $contentBlock
         */
        $this->loadModel($block->type);
        $table = $this->{$block->type};
        $contentBlock = $this->Blocks->getContentBlock($block->id);

        $viewVariables = array_merge(
            $table->getViewVariables($contentBlock),
            $viewVariables,
        );

        $owner = $this->Blocks->getOwner($contentBlock);

        $this->set(compact('contentBlock', 'owner', 'viewVariables'));
    }
}
