<?php

namespace Boot\Src\Entities;

use Boot\Src\Abstracts\JsonSerializableEntity;

/**
 * Represents the rights of an administrator in a chat.
 * @link https://core.telegram.org/bots/api#chatadministratorrights
 */
class ChatAdministratorRights extends JsonSerializableEntity
{
    /**
     * @param bool|null $isAnonymous
     * @param bool|null $canManageChat
     * @param bool|null $canDeleteMessages
     * @param bool|null $canManageVideoChats
     * @param bool|null $canRestrictMembers
     * @param bool|null $canPromoteMembers
     * @param bool|null $canChangeInfo
     * @param bool|null $canInviteUsers
     * @param bool|null $canPostMessages
     * @param bool|null $canPinMessages
     * @param bool|null $canManageTopics
     */
    public function __construct(
        public ?bool $isAnonymous = null,
        public ?bool $canManageChat = null,
        public ?bool $canDeleteMessages = null,
        public ?bool $canManageVideoChats = null,
        public ?bool $canRestrictMembers = null,
        public ?bool $canPromoteMembers = null,
        public ?bool $canChangeInfo = null,
        public ?bool $canInviteUsers = null,
        public ?bool $canPostMessages = null,
        public ?bool $canPinMessages = null,
        public ?bool $canManageTopics = null,
    ) {}
}