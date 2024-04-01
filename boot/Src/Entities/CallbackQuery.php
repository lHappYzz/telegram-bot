<?php

namespace Boot\Src\Entities;

use Boot\Interfaces\CallbackableQueryEntity;
use Boot\Responsibilities;
use Boot\Src\Abstracts\UpdateUnit;
use Boot\Src\Entities\ReplyMarkup\InlineKeyboardButton;

/**
 * Class CallbackQuery
 * @link https://core.telegram.org/bots/api#callbackquery
 */
class CallbackQuery extends UpdateUnit implements CallbackableQueryEntity
{

    public function __construct(
        protected string $id,
        protected TelegramUser $from,
        protected ?TelegramMessage $message,
        protected ?string $inlineMessageId,
        protected ?string $chatInstance,
        protected ?string $data,
        protected ?string $gameShortName,
    ) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getFrom(): TelegramUser
    {
        return $this->from;
    }

    public function getMessage(): ?TelegramMessage
    {
        return $this->message;
    }

    public function getInlineMessageId(): ?string
    {
        return $this->inlineMessageId;
    }

    public function getChatInstance(): ?string
    {
        return $this->chatInstance;
    }

    public function getGameShortName(): ?string
    {
        return $this->gameShortName;
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    public function getButtonUserData(): string
    {
        return array_last(explode(InlineKeyboardButton::CALLBACK_DATA_DELIMITER, $this->data));
    }

    public function getChat(): TelegramChat
    {
        return $this->getMessage()?->getChat();
    }

    /**
     * Method describes how and when the responsible code base should run for each of update unit
     *
     * @param Responsibilities $responsibility
     * @return void
     */
    public function responsibilize(Responsibilities $responsibility): void
    {
        $responsibility->handleCallbackQuery($this);
    }
}