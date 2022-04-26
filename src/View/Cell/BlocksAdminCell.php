<?php
namespace ContentBlocks\View\Cell;

use Cake\Datasource\EntityInterface;
use Cake\Filesystem\Folder;
use Cake\Routing\Router;
use Cake\Utility\Inflector;
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

        $availableBlocks = $this->getAvailableBlocks();

        $this->set(compact('area', 'availableBlocks'));
    }

    private function getAvailableBlocks(): array
    {
        $entities = new Folder(ROOT . DS . 'src' . DS . 'Model' . DS . 'Entity' . DS);
        $blocks = $entities->find(".*\ContentBlock.php");

        $blocks = array_map(
            function ($block) {

                try {
                    $reflectionClass = new \ReflectionClass("App\\Model\\Entity\\" . str_replace('.php', '', $block));
                    $blockTable = $this->loadModel(Inflector::pluralize($reflectionClass->getShortName()));
                    $blockEntity = $blockTable->newEntity();

                    return $blockEntity;

                } catch (\Exception $e) {
                    return null;
                }
            },
            $blocks
        );

        return array_filter(
            $blocks,
            function ($block) {
                return (bool)$block;
            }
        );
    }
}
