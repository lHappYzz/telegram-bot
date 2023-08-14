<?php

namespace Boot\Src\Abstracts;

use Boot\Interfaces\MessageableEntity;
use Boot\Src\Update;

/**
 * The main component of the telegram update
 * @see Update
 */
abstract class UpdateUnit
{
    /**
     * @return bool
     */
    public function isCommand(): bool
    {
        if ($this instanceof MessageableEntity) {
            return $this->isCommand();
        }
        return false;
    }
}