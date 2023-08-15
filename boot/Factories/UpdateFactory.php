<?php

namespace Boot\Factories;

use Boot\Src\Update;

abstract class UpdateFactory
{
    /**
     * @param string $updateId
     * @param array $components
     */
    public function __construct(
        protected string $updateId,
        protected array $components
    ) {}

    /**
     * Creates an update instance according to incoming info from telegram
     *
     * @return Update
     */
    abstract function createUpdate(): Update;
}