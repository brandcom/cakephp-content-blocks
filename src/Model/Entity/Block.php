<?php
namespace ContentBlocks\Model\Entity;

use App\View\AppView;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Entity;
use Cake\Utility\Inflector;
use ContentBlocks\Model\Table\AreasTable;

/**
 * ContentBlocksBlock Entity
 *
 * @property int $id
 * @property int $content_blocks_area_id
 * @property int|null $sort
 * @property boolean $is_published
 * @property string $type
 * @property string $html_anchor
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
        'is_published' => true,
        'type' => true,
        'html_anchor' => true,
        'created' => true,
        'modified' => true,
        'content_blocks_area' => true,
    ];

    /**
     * @var string[]
     */
    protected $_hidden = [
        "content_blocks_block_id",
        "created",
        "modified",
        "id",
        "block",
    ];

    /**
     * Array of field names that won't get an input field in the admin form
     *
     * e.g. [
     *      'id',
     *      'something_hidden',
     *      ...
     * ]
     *
     * @return string[]
     */
    protected function getHiddenFields(): array
    {
        return $this->_hidden;
    }

    /**
     * Array of field names (keys) and options for the FormHelper (values)
     *
     * e.g. [
     *      'title' => [
     *          'label' => __("The Title"),
     *          ...,
     *      ],
     *      'content' => ...
     * ]
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
        $options = [];

        foreach ($this->getFields() as $field) {
            if (!empty($field['type']) && $field['type'] === 'file') {
                $options['type'] = 'file';
            }
        }

        return $options;
    }

    public function render(array $viewVariables): string
    {
        $template_name = Inflector::underscore(str_replace("ContentBlocks", '', $this->getSource()));

        $view = new AppView();
        $viewVariables['block'] = $this;

        $pluginPrefix = rtrim($this->getPluginPrefix(), '.');
        $pluginPrefix = $pluginPrefix ? $pluginPrefix . '.' : null;

        return $view->element(
            sprintf("%scontent_blocks/%s", $pluginPrefix, $template_name),
            $viewVariables
        );
    }

    /**
     * Get a human-readable Title for the Admin area
     *
     * @return string
     */
    public function getTitle(): ?string
    {
        try {

            $className = empty($this->type) ?
                str_replace("App\\Model\\Entity\\", "", get_class($this))
                : Inflector::singularize($this->type);
            $reflectionClass = new \ReflectionClass("App\\Model\\Entity\\" . $className);

            $method = new \ReflectionMethod($reflectionClass->getName(), "getTitle");
            if ($method->class !== Block::class) {

                return $reflectionClass->newInstance()->getTitle();
            }

            return Inflector::humanize(Inflector::underscore($reflectionClass->getShortName()));

        } catch (\ReflectionException $exception) {

            debug($exception->getMessage());

            return null;
        }
    }

    /**
     * Other than getTitle, the description describes an instance,
     * not the block as the class.
     *
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->get("title") ?
            $this->getTitle() . ': ' . $this->get("title")
            : $this->getTitle();
    }

    /**
     * Return a relational array of Models the Block has a relationship with.
     *
     * Input fields will be rendered in the admin form.
     *
     * E.g. [
     *      "SlideshowImages",
     *      ...
     * ]
     *
     * @return array
     */
    public function getManagedModels(): array
    {
        return [];
    }

    /**
     * Array of Entity-Models that are allowed to have this block.
     *
     * E.g., to only allow a TextContentBlock on an Article:
     * return [
     *      "Articles",
     * ];
     *
     * An Empty array means that all Entities can have this block.
     */
    protected function getAllowedEntities(): array
    {
        return [];
    }

    /**
     * Array of Entitiy-Models that are not allowed to have this block.
     *
     * E.g. [
     *      "Articles",
     *      "Users",
     * ]
     */
    protected function getDisallowedEntities(): array
    {
        return [];
    }

    /**
     * Check if a block can be on an Entity
     *
     * @param EntityInterface|null $entity
     * @return bool
     */
    public function canBeOnEntity(?EntityInterface $entity): bool
    {
        if (null === $entity) {

            return true;
        }

        if (in_array($entity->getSource(), $this->getDisallowedEntities())) {

            return false;
        }

        return empty($this->getAllowedEntities()) || in_array($entity->getSource(), $this->getAllowedEntities());
    }

    /**
     * Control whether the Block can be added to a BlockArea or rendered
     * in the template.
     *
     * This can be useful if you want to control block usage e.g. for different modes or staging/live environments.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return true;
    }

    /**
     * If the Block's template (Element) comes from a Plugin/Theme,
     * define the Plugin's prefix.
     *
     * e.g. 'MyPlugin'
     *
     * @return string|null
     */
    public function getPluginPrefix(): ?string
    {
        return null;
    }

    /**
     * Display a helpful information for CMS authors above the edit form
     *
     * @return string|null
     */
    public function getCmsHelperText(): ?string
    {
        return null;
    }
}
