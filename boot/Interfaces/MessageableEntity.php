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
    public function getMessageText(): ?string;

    /**
     * @return int
     */
    public function getMessageId(): int;

    /**
     * TODO: Change return type to DateTime object
     * @param string $format
     * @return string
     */
    public function getMessageDate(string $format = 'Y-m-d H:i:s'): string;

    /**
     * Check if message is command
     *
     * @return bool
     */
    public function isCommand(): bool;
}