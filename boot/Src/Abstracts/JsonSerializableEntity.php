<?php

namespace Boot\Src\Abstracts;

use JsonSerializable;

abstract class JsonSerializableEntity extends Entity implements JsonSerializable
{
    /**
     * Specify data which should be serialized to JSON
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        $serializedData = [];

        foreach ($this as $fieldName => $fieldValue) {
            if ($fieldValue !== null) {
                $serializedData[camel_case_to_snake_case($fieldName)] = $fieldValue;
            }
        }

        return $serializedData;
    }
}