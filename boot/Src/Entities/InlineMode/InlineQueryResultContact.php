<?php

namespace Boot\Src\Entities\InlineMode;

use Boot\Traits\WithInputMessageContent;

/**
 * @link https://core.telegram.org/bots/api#inlinequeryresultcontact
 * @link https://en.wikipedia.org/wiki/VCard
 */
class InlineQueryResultContact extends InlineQueryResult
{
    use WithInputMessageContent;

    /**
     * @param string $id
     * @param string $phoneNumber
     * @param string $firstName
     * @param string|null $lastName
     * @param string|null $vcard
     * Additional data about the contact in the form of a vCard, 0-2048 bytes
     * @param string|null $thumbnailUrl
     * @param int|null $thumbnailWidth
     * @param int|null $thumbnailHeight
     */
    public function __construct(
        string $id,
        protected string $phoneNumber,
        protected string $firstName,
        protected ?string $lastName = null,
        protected ?string $vcard = null,
        protected ?string $thumbnailUrl = null,
        protected ?int $thumbnailWidth = null,
        protected ?int $thumbnailHeight = null,
    ) {
        parent::__construct($id);
    }

    /**
     * @inheritDoc
     */
    protected function getType(): string
    {
        return 'contact';
    }
}