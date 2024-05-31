<?php

namespace Boot\Src;

use Boot\Src\Abstracts\Entity;
use Boot\Src\Entities\TelegramMessage;

class TelegramResponse
{
    /** @var array|bool */
    protected array|bool $result;

    /**
     * @param array $rawResponse
     */
    public function __construct(array $rawResponse) {
        $this->result = $rawResponse['result'];
    }

    /**
     * @return TelegramMessage[]
     */
    public function createMessages(): array
    {
        $messages = [];

        foreach ($this->result as $item) {
            $messages[] = container(TelegramMessage::class, $item);
        }

        return $messages;
    }

    /**
     * @param string $abstract
     * @return Entity
     */
    public function createEntity(string $abstract): Entity
    {
        return container($abstract, $this->result);
    }
}