<?php

namespace Boot\Src\Entities\InlineMode\InputMessageContent;

/**
 * @link https://core.telegram.org/bots/api#inputcontactmessagecontent
 */
class InputContactMessageContent extends InputMessageContent
{
    /**
     * @param string $phoneNumber
     * @param string $firstName
     * @param string|null $lastName
     * @param string|null $vcard
     */
    public function __construct(
        protected string $phoneNumber,
        protected string $firstName,
        protected ?string $lastName = null,
        protected ?string $vcard = null,
    ) {}

    /**
     * @param string $lastName
     * @return InputContactMessageContent
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @param string $vcard
     * @return InputContactMessageContent
     */
    public function setVcard(string $vcard): self
    {
        $this->vcard = $vcard;

        return $this;
    }


}