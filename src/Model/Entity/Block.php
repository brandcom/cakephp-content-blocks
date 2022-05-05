<?php
namespace ContentBlocks\Model\Entity;

use App\View\AppView;
use Cake\Datasource\EntityInterface;
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

    protected $_hidden = [
        "content_blocks_block_id" => true,
        "created" => true,
        "modified" => true,
        "id" => true,
        "block" => true,
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
        $hidden_fields = [];

        foreach ($this->_hidden as $field => $is_hidden) {
            if ($is_hidden) {
                $hidden_fields[] = $field;
            }
        }

        return $hidden_fields;
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

        return $view->element("content_blocks/" . $template_name, $viewVariables);
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
     * Other than getTitle, the description describes an instance,
     * not the block as the class.
     *
     * @return string
     * @throws \ReflectionException
     */
    public function getDescription(): string
    {
        return $this->title ? $this->getTitle() . ': ' . $this->title : $this->getTitle();
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
     * @param EntityInterface $entity
     * @return bool
     */
    public function canBeOnEntity(EntityInterface $entity): bool
    {
        if (in_array($entity->getSource(), $this->getDisallowedEntities())) {

            return false;
        }

        return empty($this->getAllowedEntities()) || in_array($entity->getSource(), $this->getAllowedEntities());
    }
}
