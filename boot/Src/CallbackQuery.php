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
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getFrom(): telegramUser
    {
        return $this->from;
    }

    public function getMessage(): telegramMessage
    {
        return $this->message;
    }
}