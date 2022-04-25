<?php
namespace ContentBlocks\Model\Entity;

use App\View\AppView;
use Cake\ORM\Entity;
use Cake\Utility\Inflector;

/**
 * ContentBlocksBlock Entity
 *
 * @property int $id
 * @property int $content_blocks_area_id
 * @property int|null $sort
 * @property string $type
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \ContentBlocks\Model\Entity\Area $area
 */
class Block extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'content_blocks_area_id' => true,
        'sort' => true,
        'type' => true,
        'created' => true,
        'modified' => true,
        'content_blocks_area' => true,
    ];

    /**
     * Array of field names that won't get an input field in the admin form
     *
     * @return string[]
     */
    public function getHiddenFields(): array
    {
        return [
            "content_blocks_block_id",
            "created",
            "modified",
            "id",
            "block",
        ];
    }

    /**
     * Array of field names (keys) and options for the FormHelper (values)
     *
     * @return array
     */
    public function getFields(): array
    {
        $fields = [];

        foreach ($this->_accessible as $field => $is_accessible) {
            if ($is_accessible && !in_array($field, $this->getHiddenFields())) {
                $fields[$field] = [];
            }
        }

        return $fields;
    }

    /**
     * Return an options array that is passed to the admin form.
     *
     * E.g. ['type' => "file"]
     *
     * @return array
     */
    public function getFormOptions(): array
    {
        return [];
    }

    public function render(): string
    {
        $me = new \ReflectionClass(get_class($this));
        $template_name = str_replace('app\\model\\entity\\', '', Inflector::underscore($me->getName()));
        $template_name = str_replace('_content_block', '', $template_name);

        $view = new AppView();

        return $view->element("content_blocks/" . $template_name, ['block' => $this]);
    }

    public function getSetVariables(): array
    {
        return [];
    }

    /**
     * Get a human-readable Title for the Admin area
     *
     * @return string
     * @throws \ReflectionException
     */
    public function getTitle(): string
    {
        $className = empty($this->type) ?
            str_replace("App\\Model\\Entity\\", "", get_class($this))
            : Inflector::singularize($this->type);
        $reflectionClass = new \ReflectionClass("App\\Model\\Entity\\" . $className);

        $method = new \ReflectionMethod($reflectionClass->getName(), "getTitle");
        if ($method->class !== Block::class) {

            return $reflectionClass->newInstance()->getTitle();
        }

        return Inflector::humanize(Inflector::underscore($reflectionClass->getShortName()));
    }

    /**
     * Return a relational array of Models the Block has a relationship with.
     *
     * Input fields will be rendered in the admin form.
     *
     * E.g. [
     *      SlideshowImage::class => __("Slideshow Images"),
     *      ...
     * ]
     *
     * @return array
     */
    public function getManagedModels(): array
    {
        return [];
    }
}
