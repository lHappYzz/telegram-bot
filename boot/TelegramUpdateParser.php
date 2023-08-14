<?php

namespace Boot;

use Boot\Factories\CallbackableFactory;
use Boot\Factories\MessageableUpdateFactory;
use Boot\Factories\UpdateFactory;
use Boot\Log\Logger;
use JsonException;
use RuntimeException;

class TelegramUpdateParser
{
    /** @var string */
    protected const UPDATE_TYPE_MESSAGE = 'message';

    /** @var string */
    protected const UPDATE_TYPE_EDITED_MESSAGE = 'edited_message';

    /** @var string */
    protected const UPDATE_TYPE_CALLBACK_QUERY = 'callback_query';

    /** @var array|string[] */
    protected array $factoryBindings = [
        self::UPDATE_TYPE_MESSAGE => MessageableUpdateFactory::class,
        self::UPDATE_TYPE_EDITED_MESSAGE => MessageableUpdateFactory::class,
        self::UPDATE_TYPE_CALLBACK_QUERY => CallbackableFactory::class,
    ];

    /**
     * @return UpdateFactory
     */
    public function parseTelegramRequest(): UpdateFactory
    {
        try {
            $tgData = json_decode(
                file_get_contents('php://input'),
                true,
                flags: JSON_THROW_ON_ERROR
            );

            Logger::logInfo(print_r($tgData, true));

            return $this->createUpdateFactory($tgData);
        } catch (JsonException $e) {
            Logger::logException($e, Logger::LEVEL_ERROR);
            throw new RuntimeException('Failed to parse telegram request', $e->getCode(), $e);
        }
    }

    /**
     * @param array $requestData
     * @return UpdateFactory
     */
    private function createUpdateFactory(array $requestData): UpdateFactory
    {
        foreach ($this->factoryBindings as $type => $binding) {
            if (array_key_exists($type, $requestData)) {
                return new $this->factoryBindings[$type]($requestData['update_id'], $requestData[$type]);
            }
        }
        throw new RuntimeException('Update type is not supported.');
    }
}