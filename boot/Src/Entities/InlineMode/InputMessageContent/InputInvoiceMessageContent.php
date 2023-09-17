<?php

namespace Boot\Src\Entities\InlineMode\InputMessageContent;

use Boot\Src\Entities\Payments\LabeledPrice;

/**
 * @link https://core.telegram.org/bots/api#inputinvoicemessagecontent
 */
class InputInvoiceMessageContent extends InputMessageContent
{
    /**
     * @param string $title
     * @param string $description
     * @param string $payload
     * @param string $providerToken
     * @param string $currency
     * @param LabeledPrice[] $prices
     * @param int|null $maxTipAmount
     * @param array|null $suggestedTipAmounts
     * @param string|null $providerData
     * @param string|null $photoUrl
     * @param int|null $photoSize
     * @param int|null $photoWidth
     * @param int|null $photoHeight
     * @param bool $needName
     * @param bool $needPhoneNumber
     * @param bool $needEmail
     * @param bool $needShippingAddress
     * @param bool $sendPhoneNumberToProvider
     * @param bool $sendEmailToProvider
     * @param bool $isFlexible
     */
    public function __construct(
        protected string $title,
        protected string $description,
        protected string $payload,
        protected string $providerToken,
        protected string $currency,
        protected array $prices,
        protected ?int $maxTipAmount = null,
        protected ?array $suggestedTipAmounts = null,
        protected ?string $providerData = null,
        protected ?string $photoUrl = null,
        protected ?int $photoSize = null,
        protected ?int $photoWidth = null,
        protected ?int $photoHeight = null,
        protected bool $needName = false,
        protected bool $needPhoneNumber = false,
        protected bool $needEmail = false,
        protected bool $needShippingAddress = false,
        protected bool $sendPhoneNumberToProvider = false,
        protected bool $sendEmailToProvider = false,
        protected bool $isFlexible = false,
    ) {}

    /**
     * @param int $maxTipAmount
     * @return InputInvoiceMessageContent
     */
    public function setMaxTipAmount(int $maxTipAmount): self
    {
        $this->maxTipAmount = $maxTipAmount;

        return $this;
    }

    /**
     * @param array $suggestedTipAmounts
     * @return InputInvoiceMessageContent
     */
    public function setSuggestedTipAmounts(array $suggestedTipAmounts): self
    {
        $this->suggestedTipAmounts = $suggestedTipAmounts;

        return $this;
    }

    /**
     * @param string $providerData
     * @return InputInvoiceMessageContent
     */
    public function setProviderData(string $providerData): self
    {
        $this->providerData = $providerData;

        return $this;
    }

    /**
     * @param string $photoUrl
     * @return InputInvoiceMessageContent
     */
    public function setPhotoUrl(string $photoUrl): self
    {
        $this->photoUrl = $photoUrl;

        return $this;
    }

    /**
     * @param int $photoSize
     * @return InputInvoiceMessageContent
     */
    public function setPhotoSize(int $photoSize): self
    {
        $this->photoSize = $photoSize;

        return $this;
    }

    /**
     * @param int $photoWidth
     * @return InputInvoiceMessageContent
     */
    public function setPhotoWidth(int $photoWidth): self
    {
        $this->photoWidth = $photoWidth;

        return $this;
    }

    /**
     * @param int $photoHeight
     * @return InputInvoiceMessageContent
     */
    public function setPhotoHeight(int $photoHeight): self
    {
        $this->photoHeight = $photoHeight;

        return $this;
    }

    /**
     * @return InputInvoiceMessageContent
     */
    public function needName(): self
    {
        $this->needName = true;

        return $this;
    }

    /**
     * @return InputInvoiceMessageContent
     */
    public function needPhoneNumber(): self
    {
        $this->needPhoneNumber = true;

        return $this;
    }

    /**
     * @return InputInvoiceMessageContent
     */
    public function needEmail(): self
    {
        $this->needEmail = true;

        return $this;
    }

    /**
     * @return InputInvoiceMessageContent
     */
    public function needShippingAddress(): self
    {
        $this->needShippingAddress = true;

        return $this;
    }

    /**
     * @return InputInvoiceMessageContent
     */
    public function sendPhoneNumberToProvider(): self
    {
        $this->sendPhoneNumberToProvider = true;

        return $this;
    }

    /**
     * @return InputInvoiceMessageContent
     */
    public function sendEmailToProvider(): self
    {
        $this->sendEmailToProvider = true;

        return $this;
    }

    /**
     * @return InputInvoiceMessageContent
     */
    public function isFlexible(): self
    {
        $this->isFlexible = true;

        return $this;
    }

    
}