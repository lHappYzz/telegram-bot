<?php

namespace Boot\Src;

use Boot\Application;
use Boot\Interfaces\CallbackableQueryEntity;
use Boot\Interfaces\MessageableEntity;
use Boot\Src\Abstracts\UpdateUnit;
use Boot\Src\Entities\TelegramMessage;

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

    /**
     * @return bool
     */
    public function isMessageableUpdate(): bool
    {
        return $this->updateUnit instanceof MessageableEntity;
    }

    /**
     * @return bool
     */
    public function isCallbackableQueryUpdate(): bool
    {
        return $this->updateUnit instanceof CallbackableQueryEntity;
    }

    /**
     * @return void
     */
    public function tryBootCommand(): void
    {
        if ($this->updateUnit instanceof TelegramMessage) {
            if ($this->updateUnit->isCommand()) {
                Application::bootCommand($this->updateUnit->getCommandClassName(), $this->updateUnit);
            }
        }
    }
}