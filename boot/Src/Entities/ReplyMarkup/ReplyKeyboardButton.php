<?php

namespace Boot\Src\Entities\ReplyMarkup;

use Boot\Interfaces\Keyboard\KeyboardButtonInterface;
use Boot\Src\Abstracts\JsonSerializableEntity;
use Boot\Src\Entities\ChatAdministratorRights;
use Boot\Src\Entities\ReplyMarkup\KeyboardFields\KeyboardButtonPollType;
use Boot\Src\Entities\ReplyMarkup\KeyboardFields\KeyboardButtonRequestChat;
use Boot\Src\Entities\ReplyMarkup\KeyboardFields\KeyboardButtonRequestUser;

/**
 * This object represents one button of the reply keyboard.
 * For simple text buttons, String can be used instead of this object to specify the button text.
 * The optional fields web_app, request_user, request_chat, request_contact, request_location, and request_poll are mutually exclusive.
 * @link https://core.telegram.org/bots/api#keyboardbutton
 */
class ReplyKeyboardButton extends JsonSerializableEntity implements KeyboardButtonInterface
{
    /**
     * @param string $text
     * @param KeyboardButtonRequestUser|null $requestUser
     * @param KeyboardButtonRequestChat|null $requestChat
     * @param bool|null $requestContact
     * @param bool|null $requestLocation
     * @param KeyboardButtonPollType|null $requestPoll
     */
    public function __construct(
        protected string $text,
        protected ?KeyboardButtonRequestUser $requestUser = null,
        protected ?KeyboardButtonRequestChat $requestChat = null,
        protected ?bool $requestContact = null,
        protected ?bool $requestLocation = null,
        protected ?KeyboardButtonPollType $requestPoll = null,
    ) {}

    /**
     * @return void
     */
    public function requestContact(): void
    {
        $this->requestContact = true;
    }

    /**
     * @return void
     */
    public function requestLocation(): void
    {
        $this->requestLocation = true;
    }

    /**
     * @param int $requestId
     * Signed 32-bit identifier of the request, which will be received back in the ChatShared object.
     * Must be unique within the message
     * @param bool|null $userIsBot
     * @param bool|null $userIsPremium
     * @return void
     */
    public function requestUser(
        int $requestId,
        ?bool $userIsBot = null,
        ?bool $userIsPremium = null,
    ): void {
        $this->requestUser = new KeyboardButtonRequestUser(...func_get_args());
    }

    /**
     * @param int $requestId
     * Signed 32-bit identifier of the request, which will be received back in the ChatShared object.
     * Must be unique within the message
     * @param bool $chatIsChannel
     * @param bool|null $chatIsForum
     * @param bool|null $chatHasUsername
     * @param bool|null $chatIsCreated
     * @param ChatAdministratorRights|null $userAdministratorRights
     * @param ChatAdministratorRights|null $botAdministratorRights
     * @param bool|null $botIsMember
     * @return void
     */
    public function requestChat(
        int $requestId,
        bool $chatIsChannel,
        ?bool $chatIsForum = null,
        ?bool $chatHasUsername = null,
        ?bool $chatIsCreated = null,
        ?ChatAdministratorRights $userAdministratorRights = null,
        ?ChatAdministratorRights $botAdministratorRights = null,
        ?bool $botIsMember = null,
    ): void {
        $this->requestChat = new KeyboardButtonRequestChat(...func_get_args());
    }

    /**
     * @param string $type
     * @return void
     * @see KeyboardButtonPollType
     */
    public function requestPoll(string $type): void
    {
        $this->requestPoll = new KeyboardButtonPollType($type);
    }
}