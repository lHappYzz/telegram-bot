<?php

namespace App;

use Boot\Classes\MethodOptionalFields;
use Boot\Facades\TelegramFacade;
use Boot\Src\Entities\ReplyMarkup\ReplyMarkup;
use Boot\Src\Entities\TelegramMessage;

class Bot
{
    public function __construct(
        private TelegramFacade $telegramFacade,
        private string $token
    ) {}

    public function sendMessage(
        string $text,
        string $chatId,
        ?ReplyMarkup $replyMarkup = null,
        ?string $parseMode = null,
        bool $disableWebPagePreview = false,
        ?MethodOptionalFields $optionalFields = null
    ): TelegramMessage {
        return $this
            ->telegramFacade
            ->sendMessage($this->token, ...func_get_args());
    }

    public function editMessageText(
        string $text,
        string $chatId,
        int $messageId,
        ?ReplyMarkup $replyMarkup = null,
        ?string $parseMode = null,
        bool $disableWebPagePreview = false
    ): TelegramMessage {
        return $this
            ->telegramFacade
            ->editMessageText($this->token, ...func_get_args());
    }

    public function answerCallbackQuery(
        string $callbackQueryId,
        ?string $text = null,
        ?bool $showAlert = null,
        ?string $url = null,
        ?int $cacheTime = null
    ): void {
        $this->telegramFacade->answerCallbackQuery(
            $this->token,
            ...func_get_args()
        );
    }

    public function answerInlineQuery(
        string $inlineQueryId,
        array $results,
        ?int $cacheTime = null,
        ?bool $isPersonal = null,
        ?string $nextOffset = null,
    ): void {
        $this->telegramFacade->answerInlineQuery(
            $this->token,
            ...func_get_args()
        );
    }
}