<?php

namespace Boot\Src\APIMethods;

class GetFile extends TelegramMethod
{
    /**
     * @param string $fileId
     */
    public function __construct(
        protected string $fileId,
    ) {}

    /**
     * @inheritDoc
     */
    public function getMethodName(): string
    {
        return 'getFile';
    }

    /**
     * @inheritDoc
     */
    public function validate(): void
    {
        //
    }
}