<?php

namespace Boot\Src;

use Boot\Src\APIMethods\TelegramMethod;
use Boot\Src\Exceptions\Request\TelegramRequestException;
use Boot\Src\Exceptions\Request\TelegramRequestFailedException;
use Boot\Src\Exceptions\TelegramMethod\TelegramMethodException;
use Boot\Traits\Http;
use RuntimeException;

class TelegramRequest
{
    use Http;

    /**
     * @param string $token
     */
    public function __construct(private readonly string $token) {}

    /**
     * @var TelegramMethod|null
     */
    protected ?TelegramMethod $method = null;

    /**
     * @throws TelegramRequestException
     */
    public function prepare(TelegramMethod $method): self
    {
        try {
            $method->validate();

            $this->method = $method;

            return $this;
        } catch (TelegramMethodException $e) {
            throw new TelegramRequestException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @return TelegramResponse
     * @throws TelegramRequestFailedException
     */
    public function send(): TelegramResponse
    {
        if ($this->method === null) {
            throw new RuntimeException('TelegramRequest is not prepared.');
        }

        $result = $this::sendTelegramRequest(
            array_merge($this->method->jsonSerialize(), ['token' => $this->token, 'method' => $this->method->getMethodName()])
        );

        if (!$result['ok']) {
            throw new TelegramRequestFailedException(
                'Telegram request is not OK. Error code: ' . $result['error_code'] . PHP_EOL .
                'Description: ' . $result['description'] . PHP_EOL .
                'Parameters: ' . json_encode($this->method)
            );
        }

        /** @var TelegramResponse $response */
        return container(TelegramResponse::class, [
            'rawResponse' => array_keys_to_camel_case($result)
        ]);
    }
}