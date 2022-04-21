<?php
namespace ContentBlocks\View\Cell;

use Cake\Datasource\EntityInterface;
use Cake\Routing\Router;
use Cake\View\Cell;
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
     * @return void
     */
    public function display(EntityInterface $entity)
    {
        $area = $this->Areas->findOrCreateForEntity($entity);

        $this->set(compact('area'));
    }
}
