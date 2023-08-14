<?php

namespace Boot\Src;

use Boot\Src\Abstracts\UpdateUnit;

/**
 * Class Update
 * @link https://core.telegram.org/bots/api#getting-updates
 */
class Update
{
    /**
     * Update constructor.
     * @param string $updateId
     * @param UpdateUnit $updateUnit
     */
    public function __construct(
        public string $updateId,
        public UpdateUnit $updateUnit
    ) {}
}