<?php

namespace ContentBlocks\Model\Entity\Traits;

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
}