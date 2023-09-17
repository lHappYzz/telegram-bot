<?php

namespace Boot\Factories;

use Boot\Src\Entities\InlineQuery;
use Boot\Src\Update;

class InlineQueryUpdateFactory extends UpdateFactory
{
    /**
     * @inheritDoc
     */
    function createUpdate(): Update
    {
        return new Update(
            $this->updateId,
            container(InlineQuery::class, $this->components),
        );
    }
}