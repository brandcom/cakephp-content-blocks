<?php

namespace ContentBlocks\Model\Entity\Traits;

use Cake\Utility\Inflector;

/**
 * Trait for Entities that belong to a ContentBlock
 */
trait BelongsToBlockTrait
{
    /**
     * Array of field names that won't get an input field in the admin form
     *
     * @return string[]
     */
    public function getHiddenFields(): array
    {
        return [
            "created",
            "modified",
            "id",
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

        foreach ($this->_accessible ?? [] as $field => $is_accessible) {
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

    /**
     * Human-readable title for the block type
     *
     * @return string
     */
    public function getTitle(): string
    {
        return Inflector::humanize(Inflector::underscore((new \ReflectionClass($this))->getShortName()));
    }

    /**
     * Description of a block's instance
     *
     * Returns the title field from the database by default.
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->get("title") ?: $this->getTitle();
    }
}
