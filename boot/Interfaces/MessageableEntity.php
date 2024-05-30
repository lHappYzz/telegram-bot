<?php

namespace Boot\Interfaces;

use Boot\Src\Entities\TelegramChat;
use Boot\Src\Entities\TelegramUser;

/**
 * Represents incoming message
 */
interface MessageableEntity
{
    /**
     * Returns the chat from which the message received
     *
     * @return TelegramChat
     */
    public function getChat(): TelegramChat;

    /**
     * Returns an object that represents a Telegram user or bot
     *
     * @return TelegramUser
     */
    public function getFrom(): TelegramUser;

    /**
     * UTF-8 text of the message
     * 
     * @return ?string
     */
    public function getText(): ?string;

    /**
     * @return int
     */
    public function getMessageId(): int;

    /**
     * Date the message was sent in Unix time. It is always a positive number, representing a valid date.
     *
     * @return int
     */
    public function getDate(): int;

    /**
     * Check if message is command
     *
     * @return bool
     */
    public function isCommand(): bool;
}