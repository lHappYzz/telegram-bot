<?php

namespace Boot\Src\Abstracts;

use Boot\Classes\MethodOptionalFields;
use Boot\Src\Entities\InputFile;
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
                if ($fieldValue instanceof InputFile) {
                    $fieldValue = $fieldValue->getFile();
                }
                if ($fieldValue instanceof MethodOptionalFields) {
                    $serializedData = array_merge($serializedData, $fieldValue->jsonSerialize());
                    continue;
                }
                $serializedData[camel_case_to_snake_case($fieldName)] = $fieldValue;
            }
        }

        return $serializedData;
    }
}