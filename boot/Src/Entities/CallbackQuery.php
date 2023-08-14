<?php

namespace Boot\Src\Entities;

use Boot\Interfaces\CallbackableQueryEntity;
use Boot\Src\Abstracts\UpdateUnit;
use Boot\Src\ReplyMarkup\InlineKeyboardButton;
use Boot\Traits\Helpers;

/**
 * Class CallbackQuery
 * @link https://core.telegram.org/bots/api#callbackquery
 */
class CallbackQuery extends UpdateUnit implements CallbackableQueryEntity
{
    use Helpers;

    private string $id;
    private TelegramUser $from;
    private ?TelegramMessage $message;
    private ?string $inlineMessageId;
    private ?string $chatInstance;
    private ?string $data;
    private ?string $gameShortName;

    public function __construct(array $callbackQueryData)
    {
        $this->id = $callbackQueryData['id'];
        $this->from = new TelegramUser($callbackQueryData['from']);
        $this->message = new TelegramMessage($callbackQueryData['message']);
        $this->inlineMessageId = $callbackQueryData['inline_message_id'];
        $this->chatInstance = $callbackQueryData['chat_instance'];
        $this->data = $callbackQueryData['data'];
        $this->gameShortName = $callbackQueryData['game_short_name'];
    }

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
        return $this->arrayLast(explode(InlineKeyboardButton::CALLBACK_DATA_DELIMITER, $this->data));
    }

    public function getChat(): TelegramChat
    {
        return $this->getMessage()?->getChat();
    }
}