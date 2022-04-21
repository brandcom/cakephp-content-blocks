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

    public function getFields(): array
    {
        $fields = [];
        foreach ($this->_accessible as $field => $is_accessible) {
            if ($is_accessible) {
                $fields[$field] = [];
            }
        }

        return $fields;
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
}
