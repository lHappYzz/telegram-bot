<?php

namespace Boot\Src;

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
     * @return TelegramMessage
     */
    public function createMessage(): TelegramMessage
    {
        return container(TelegramMessage::class, $this->result);
    }
}