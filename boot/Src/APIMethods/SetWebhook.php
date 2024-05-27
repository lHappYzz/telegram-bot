<?php

namespace Boot\Src\APIMethods;

use Boot\Src\Exceptions\TelegramMethod\InvalidTelegramMethodFieldException;

class SetWebhook extends TelegramMethod
{
    /**
     * @param string $url
     * @param string|null $ipAddress
     * @param int|null $maxConnections
     * @param array|null $allowUpdates
     * @param bool $dropPendingUpdates
     * @param string|null $secretToken
     */
    public function __construct(
        public string $url,
        public ?string $ipAddress = null,
        public ?int $maxConnections = null,
        public ?array $allowUpdates = null,
        public bool $dropPendingUpdates = false,
        public ?string $secretToken = null,
    ) {}

    /**
     * @inheritDoc
     */
    public function validate(): void
    {
        if ($this->secretToken !== null && (mb_strlen($this->secretToken) > 256 || mb_strlen($this->secretToken) < 1)) {
            throw new InvalidTelegramMethodFieldException('The secret token length must be between 1 and 256 characters. ');
        }

        if (!preg_match('/^[A-Za-z0-9_-]+$/', $this->secretToken)) {
            throw new InvalidTelegramMethodFieldException("The secret token can only contain characters A-Z, a-z, 0-9, _, and -.");
        }
    }

    /**
     * @inheritDoc
     */
    public function getMethodName(): string
    {
        return 'setWebhook';
    }
}