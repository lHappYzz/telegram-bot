<?php

namespace Boot\Src\Entities\InlineMode;

use Boot\Src\Entities\InlineMode\InputMessageContent\InputContactMessageContent;
use Boot\Src\Entities\InlineMode\InputMessageContent\InputInvoiceMessageContent;
use Boot\Src\Entities\InlineMode\InputMessageContent\InputLocationMessageContent;
use Boot\Src\Entities\InlineMode\InputMessageContent\InputMessageContent;
use Boot\Src\Entities\InlineMode\InputMessageContent\InputTextMessageContent;
use Boot\Src\Entities\InlineMode\InputMessageContent\InputVenueMessageContent;
use Boot\Src\Entities\ReplyMarkup\ReplyMarkup;

/**
 * @link https://core.telegram.org/bots/api#inlinequeryresultarticle
 */
class InlineQueryResultArticle extends InlineQueryResult
{
    /** @var InputMessageContent */
    protected InputMessageContent $inputMessageContent;

    /**
     * @param string $id
     * @param string $title
     * @param ReplyMarkup|null $inlineKeyboardMarkup
     * @param string|null $url
     * @param bool|null $hideUrl
     * @param string|null $description
     * @param string|null $thumbnailUrl
     * @param int|null $thumbnailWidth
     * @param int|null $thumbnailHeight
     */
    public function __construct(
        string $id,
        protected string $title,
        protected ?ReplyMarkup $inlineKeyboardMarkup = null,
        protected ?string $url = null,
        protected ?bool $hideUrl = null,
        protected ?string $description = null,
        protected ?string $thumbnailUrl = null,
        protected ?int $thumbnailWidth = null,
        protected ?int $thumbnailHeight = null,
    ) {
        parent::__construct($id);
    }

    /**
     * @param string $messageText
     * @return InputTextMessageContent
     */
    public function addText(string $messageText): InputTextMessageContent
    {
        return $this->inputMessageContent = new InputTextMessageContent($messageText);
    }

    /**
     * @param float $latitude
     * @param float $longitude
     * @return InputLocationMessageContent
     */
    public function addLocation(float $latitude, float $longitude): InputLocationMessageContent
    {
        return $this->inputMessageContent = new InputLocationMessageContent(...func_get_args());
    }

    /**
     * @param float $latitude
     * @param float $longitude
     * @param string $title
     * @param string $address
     * @return InputVenueMessageContent
     */
    public function addVenue(
        float $latitude,
        float $longitude,
        string $title,
        string $address
    ): InputVenueMessageContent {
        return $this->inputMessageContent = new InputVenueMessageContent(...func_get_args());
    }

    /**
     * @param string $phoneNumber
     * @param string $firstName
     * @return InputContactMessageContent
     */
    public function addContact(string $phoneNumber, string $firstName): InputContactMessageContent
    {
        return $this->inputMessageContent = new InputContactMessageContent(...func_get_args());
    }

    /**
     * @param string $title
     * @param string $description
     * @param string $payload
     * @param string $providerToken
     * @param string $currency
     * @param array $prices
     * @return InputInvoiceMessageContent
     * @link https://core.telegram.org/bots/payments#supported-currencies
     */
    public function addInvoice(
        string $title,
        string $description,
        string $payload,
        string $providerToken,
        string $currency,
        array $prices
    ): InputInvoiceMessageContent {
        return $this->inputMessageContent = new InputInvoiceMessageContent(...func_get_args());
    }

    /**
     * @inheritDoc
     */
    protected function getType(): string
    {
        return 'article';
    }
}