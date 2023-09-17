<?php

namespace Boot\Src\Entities\Payments;

use Boot\Src\Abstracts\JsonSerializableEntity;

/**
 * @link https://core.telegram.org/bots/api#labeledprice
 */
class LabeledPrice extends JsonSerializableEntity
{
    /**
     * @param string $label
     * @param int $amount
     */
    public function __construct(
        protected string $label,
        protected int $amount
    ) {}
}