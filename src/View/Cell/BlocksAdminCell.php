<?php
namespace ContentBlocks\View\Cell;

use Cake\Datasource\EntityInterface;
use Cake\Filesystem\Folder;
use Cake\Routing\Router;
use Cake\Utility\Inflector;
use Cake\View\Cell;
use ContentBlocks\Model\Entity\Block;
use ContentBlocks\Model\Table\AreasTable;

/**
 * BlocksAdmin cell
 *
 * @property AreasTable $Areas
 */
class BlocksAdminCell extends Cell
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
     * @param EntityInterface|string $entityOrKey
     * @return void
     */
    public function display($entityOrKey)
    {
        $area = $this->Areas->findOrCreateForEntityOrKey($entityOrKey);

        $availableBlocks = $this->Areas->getAvailableBlocks($entityOrKey);

        $this->set(compact('area', 'availableBlocks'));
    }
}
