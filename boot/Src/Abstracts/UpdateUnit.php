<?php

namespace Boot\Src\Abstracts;

use Boot\Interfaces\MessageableEntity;
use Boot\Interfaces\Responsibility;
use Boot\Src\Update;

/**
 * The main component of the telegram update
 * @see Update
 */
abstract class UpdateUnit implements Responsibility
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