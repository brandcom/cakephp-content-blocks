<?php
namespace ContentBlocks\View\Helper;

use Cake\Filesystem\Folder;
use Cake\View\Helper;

/**
 * ContentBlocksAdmin helper
 *
 * @property Helper\FormHelper $Form
 */
class ContentBlocksAdminHelper extends Helper
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    public $helpers = [
        'Form',
    ];

    public function getBlockList(): array
    {
        $entities = new Folder(ROOT . DS . 'src' . DS . 'Model' . DS . 'Entity' . DS);
        $blocks = $entities->find(".*\ContentBlock.php");

        $blocks = array_map(
            function ($block) {

                try {
                    $reflectionClass = new \ReflectionClass("App\\Model\\Entity\\" . str_replace('.php', '', $block));

                    return $reflectionClass->newInstance();

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
