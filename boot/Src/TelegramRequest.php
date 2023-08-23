<?php

namespace Boot\Src;

use App\Config\Config;
use Boot\TelegramUpdateParser;
use Boot\Traits\Http;

class TelegramRequest
{
    use Http;

    /**
     * An object created from JSON sent by telegram
     *
     * @var Update
     * @see parseTelegramRequest
     */
    protected Update $update;

    public function __construct(TelegramUpdateParser $telegramUpdateParser)
    {
        $this->update = $telegramUpdateParser->parseTelegramRequest()->createUpdate();
    }

    /**
     * @return Update
     */
    public function getUpdate(): Update
    {
        return $this->update;
    }

    /**
     * Set telegram webhook according to application configuration
     */
    public static function setWebhook()
    {
        $botConfig = Config::bot();
        return self::sendTelegramRequest(['token' => $botConfig['bot_token'], 'method' => 'setWebhook', 'url' => 'https://' . $botConfig['bot_url']]);
    }
    /**
     * Remove telegram webhook
     */
    public static function removeWebhook()
    {
        return self::sendTelegramRequest(['token' => Config::bot()['bot_token'], 'method' => 'setWebhook', 'url' => '']);
    }
}