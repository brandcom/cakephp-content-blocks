<?php
namespace ContentBlocks\View\Helper;

use Cake\Filesystem\Folder;
use Cake\View\Helper;
use ContentBlocks\Model\Entity\Block;

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

        $blocks = array_filter(
            $blocks,
            function ($block) {
                $reflectionClass = new \ReflectionClass("App\\Model\\Entity\\" .  str_replace('.php', '', $block));

                return $reflectionClass->getParentClass()->getName() === Block::class;
            }
        );

        return array_map(
            function ($block) {
                return str_replace('ContentBlock.php', '', $block);
            },
            $blocks
        );
    }

    public function getFormControl(string $title, $config): string
    {
        $type = null;
        if (is_string($config)) {
            $type = $config;
        } elseif (is_array($config)) {
            $type = $config['type'];
        }

        if ($type === "Enum") {

            return $this->getSelect($title, $config['options'] ?? []);
        }

        switch ($type) {
            case "HTMLText":
            case "Text":
                return $this->Form->control($title, [
                    'type' => "textarea",
                    'class' => 'content-field--' . strtolower($type)
                ]);
        }

        return '';
    }

    private function getSelect(string $title, array $options): string
    {
        return $this->Form->control($title, [
            'options' => $options,
        ]);
    }
}
