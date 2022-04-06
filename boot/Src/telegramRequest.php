<?php

namespace Boot\Src;

use App\Config\Config;
use Boot\Traits\http;
use Exception;
use Boot\Log\Logger;

class telegramRequest
{

    use http;

    /**
     * An object created from JSON sent by telegram
     *
     * @var Update
     * @see parseTelegramRequest
     */
    public Update $update;

    public function __construct()
    {
        $this->update = $this->parseTelegramRequest();
    }

    private function parseTelegramRequest(): Update
    {
        try {
            $tgData = json_decode(file_get_contents('php://input'), 1, 512, JSON_THROW_ON_ERROR);

            Logger::logInfo(print_r($tgData, true));

            return new Update($tgData);
        } catch (Exception $e) {
            Logger::logException($e, Logger::LEVEL_ERROR);
            die();
        }
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