<?php

namespace Boot\Src;

use App\Config\Config;
use Boot\Traits\http;
use Exception;
use Boot\Log\Logger;

class telegramRequest {

    use http;

    /**
     * An array created from json object sent by telegram
     *
     * @var array
     * @see parseTelegramRequest
     */
    private array $update;

    private array $updateTypes = [
        'message',
        'edited_message'
    ];

    private string $updateType;

    public function __construct()
    {
        $this->update = $this->parseTelegramRequest();
    }

    public function setUpdateType(array $update): void
    {
        foreach ($this->updateTypes as $type) {
            if (array_key_exists($type, $update)) {
                $this->updateType = $type;
                return;
            }
        }
        throw new Exception('Can not recognize telegram update type.');
    }

    private function parseTelegramRequest(): array
    {
        try {
            $tgData = json_decode(file_get_contents('php://input'), 1, 512, JSON_THROW_ON_ERROR);
            $this->setUpdateType($tgData);

            Logger::logInfo(print_r($tgData, true));

            return $tgData;
        } catch (Exception $e) {
            Logger::logException($e, Logger::LEVEL_ERROR);
            die();
        }
    }

    /**
     * Returns array data created from json object sent by telegram
     *
     * @see parseTelegramRequest
     * @return array
     */
    public function getUpdate(): array
    {
        return $this->update;
    }

    public function getUpdateType(): string
    {
        return $this->updateType;
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