<?php

namespace Boot\Factories;

use Boot\Src\Entities\CallbackQuery;
use Boot\Src\Update;

class CallbackableFactory extends UpdateFactory
{
    /**
     * @return Update
     */
    function createUpdate(): Update
    {
        return new Update(
            $this->updateId,
            new CallbackQuery($this->components)
        );
    }
}