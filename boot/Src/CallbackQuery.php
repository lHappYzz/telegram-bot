<?php

namespace Boot\Src;

/**
 * Class CallbackQuery
 * @link https://core.telegram.org/bots/api#callbackquery
 */
class CallbackQuery extends Entity
{
    private string $id;
    private telegramUser $from;
    private ?telegramMessage $message;
    private ?string $inlineMessageId;
    private ?string $chatInstance;
    private ?string $data;
    private ?string $gameShortName;

    public function __construct(array $callbackQueryData)
    {
        $this->id = $callbackQueryData['id'];
        $this->from = new telegramUser($callbackQueryData['from']);
        $this->message = new telegramMessage($callbackQueryData['message']);
        $this->inlineMessageId = $callbackQueryData['inline_message_id'];
        $this->chatInstance = $callbackQueryData['chat_instance'];
        $this->data = $callbackQueryData['data'];
        $this->gameShortName = $callbackQueryData['game_short_name'];
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getFrom(): telegramUser
    {
        return $this->from;
    }

    public function getMessage(): ?telegramMessage
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
}