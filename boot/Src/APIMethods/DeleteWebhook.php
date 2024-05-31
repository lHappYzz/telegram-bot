<?php

namespace Boot\Src\APIMethods;

class DeleteWebhook extends TelegramMethod
{
    /**
     * @param bool $dropPendingUpdates
     */
    public function __construct(public bool $dropPendingUpdates = false) {}

    /**
     * @inheritDoc
     */
    public function validate(): void
    {
        //
    }

    /**
     * @inheritDoc
     */
    public function getMethodName(): string
    {
        return 'deleteWebhook';
    }
}