<?php

namespace Boot\Traits;

use Boot\Src\Entities\InlineMode\InputMessageContent\InputContactMessageContent;
use Boot\Src\Entities\InlineMode\InputMessageContent\InputInvoiceMessageContent;
use Boot\Src\Entities\InlineMode\InputMessageContent\InputLocationMessageContent;
use Boot\Src\Entities\InlineMode\InputMessageContent\InputMessageContent;
use Boot\Src\Entities\InlineMode\InputMessageContent\InputTextMessageContent;
use Boot\Src\Entities\InlineMode\InputMessageContent\InputVenueMessageContent;

/**
 * @link https://core.telegram.org/bots/api#inputmessagecontent
 */
trait WithInputMessageContent
{
    protected ?InputMessageContent $inputMessageContent = null;

    /**
     * @param string $messageText
     * @return InputTextMessageContent
     */
    public function setTextMessageContent(string $messageText): InputTextMessageContent
    {
        return $this->inputMessageContent = new InputTextMessageContent($messageText);
    }

    /**
     * @param float $latitude
     * @param float $longitude
     * @return InputLocationMessageContent
     */
    public function setLocationMessageContent(float $latitude, float $longitude): InputLocationMessageContent
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
    public function setVenueMessageContent(
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
    public function setContactMessageContent(string $phoneNumber, string $firstName): InputContactMessageContent
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
    public function setInvoiceMessageContent(
        string $title,
        string $description,
        string $payload,
        string $providerToken,
        string $currency,
        array $prices
    ): InputInvoiceMessageContent {
        return $this->inputMessageContent = new InputInvoiceMessageContent(...func_get_args());
    }
}