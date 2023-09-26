<?php

namespace Boot\Src\Abstracts;

use Boot\Src\TelegramRequest;
use Boot\Src\TelegramResponse;
use RuntimeException;

abstract class Telegram
{
    /** @var string */
    public const COMMANDS_NAMESPACE = 'App\\Commands\\';

    /** @var string */
    public const CALLBACK_QUERY_NAMESPACE = 'App\\CallbackQueryHandlers\\';

    /**
     * @param TelegramRequest $request
     */
    public function __construct(protected TelegramRequest $request) {}

    /**
     * @param array $parameters
     * @return TelegramResponse
     */
    protected function sendTelegramRequest(array $parameters): TelegramResponse
    {
        $result = $this->request::sendTelegramRequest($parameters);

        if (!$result['ok']) {
            unset($parameters['token']);
            throw new RuntimeException(
                'Telegram request is not OK. Error code: ' . $result['error_code'] . PHP_EOL .
                'Description: ' . $result['description'] . PHP_EOL .
                'Parameters: ' . json_encode($parameters)
            );
        }

        /** @var TelegramResponse $response */
        return container(TelegramResponse::class, [
            'rawResponse' => $result
        ]);
    }
}