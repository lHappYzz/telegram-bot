<?php

namespace Boot\Src;

use Boot\TelegramUpdateParser;

class TelegramWebhook
{
    /**
     * An object created from JSON sent by telegram
     *
     * @var Update|null
     * @see parseTelegramRequest
     */
    private ?Update $update = null;

    /**
     * Parse Update from php://input
     *
     * @return Update
     */
    public function getUpdate(): Update
    {
        return $this->update ?? $this->update = container(TelegramUpdateParser::class)
            ->parseTelegramRequest()
            ->createUpdate();
    }
}