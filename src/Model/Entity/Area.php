<?php
namespace ContentBlocks\Model\Entity;

use Cake\ORM\Entity;

/**
 * ContentBlocksArea Entity
 *
 * @property int $id
 * @property string $owner_model
 * @property string $owner_id
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \ContentBlocks\Model\Entity\Block[] $blocks
 */
class Area extends Entity
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
        'owner_model' => true,
        'owner_id' => true,
        'created' => true,
        'modified' => true,
        'owner' => true,
        'content_blocks_blocks' => true,
    ];
}
