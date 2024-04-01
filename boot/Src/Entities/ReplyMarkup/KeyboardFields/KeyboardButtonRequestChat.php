<?php

namespace Boot\Src\Entities\ReplyMarkup\KeyboardFields;

use Boot\Src\Abstracts\JsonSerializableEntity;
use Boot\Src\Entities\ChatAdministratorRights;

/**
 * This object defines the criteria used to request a suitable chat.
 * The identifier of the selected chat will be shared with the bot when the corresponding button is pressed.
 * @link https://core.telegram.org/bots/api#keyboardbuttonrequestchat
 */
class KeyboardButtonRequestChat extends JsonSerializableEntity
{
    /**
     * @param int $requestId
     * @param bool $chatIsChannel
     * @param bool|null $chatIsForum
     * @param bool|null $chatHasUsername
     * @param bool|null $chatIsCreated
     * @param ChatAdministratorRights|null $userAdministratorRights
     * @param ChatAdministratorRights|null $botAdministratorRights
     * @param bool|null $botIsMember
     */
    public function __construct(
        protected int $requestId,
        protected bool $chatIsChannel = false,
        protected ?bool $chatIsForum = null,
        protected ?bool $chatHasUsername = null,
        protected ?bool $chatIsCreated = null,
        protected ?ChatAdministratorRights $userAdministratorRights = null,
        protected ?ChatAdministratorRights $botAdministratorRights = null,
        protected ?bool $botIsMember = null,
    ) {}
}