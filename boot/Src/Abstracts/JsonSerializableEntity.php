<?php

namespace Boot\Src\Abstracts;

use Boot\Traits\Helpers;
use JsonSerializable;

abstract class JsonSerializableEntity extends Entity implements JsonSerializable
{
    use Helpers;

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
                $serializedData[$this->camelCaseToSnakeCase($fieldName)] = $fieldValue;
            }
        }

        return $serializedData;
    }
}