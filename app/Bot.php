<?php

namespace App;

use App\Config\Config;
use Boot\Facades\TelegramFacade;
use Boot\Log\Logger;
use Boot\Src\Entities\ReplyMarkup\ReplyMarkup;
use Boot\Src\Entities\TelegramChat;
use Boot\Src\TelegramFile;
use Throwable;

class Bot
{
    public function __construct(
        private TelegramFacade $telegramFacade,
        private ?string $token = null
    ) {
        $this->token = Config::bot()['bot_token'];
    }

    public function sendMessage(
        string $text,
        TelegramChat $chat,
        ?ReplyMarkup $replyMarkup = null,
        ?string $parseMode = null,
        bool $disableWebPagePreview = false,
        bool $disableNotification = false,
        bool $protectContent = false,
        ?string $replyToMessageId = null,
        bool $allowSendingWithoutReply = false
    ): void {
        try {
            $this->telegramFacade->sendTelegramRequest([
                'token' => $this->token,
                'method' => 'sendMessage',
                'text' => $text,
                'chat_id' => $chat->getId(),
                'parse_mode' => $parseMode,
                'disable_web_page_preview' => $disableWebPagePreview,
                'disable_notification' => $disableNotification,
                'protect_content' => $protectContent,
                'reply_to_message_id' => $replyToMessageId,
                'allow_sending_without_reply' => $allowSendingWithoutReply,
                'reply_markup' => $replyMarkup ? json_encode($replyMarkup, JSON_THROW_ON_ERROR) : null,
            ]);
        } catch (Throwable $e) {
            Logger::logException($e, Logger::LEVEL_ERROR);
        }
    }

    public function editMessageText(
        string $text,
        TelegramChat $chat,
        int $messageId,
        ?ReplyMarkup $replyMarkup = null,
        ?string $parseMode = null,
        bool $disableWebPagePreview = false
    ): void {
        try {
            $this->telegramFacade->sendTelegramRequest([
                'token' => $this->token,
                'method' => 'editMessageText',
                'text' => $text,
                'chat_id' => $chat->getId(),
                'message_id' => $messageId,
                'parse_mode' => $parseMode,
                'disable_web_page_preview' => $disableWebPagePreview,
                'reply_markup' => $replyMarkup ? json_encode($replyMarkup, JSON_THROW_ON_ERROR) : null,
            ]);
        } catch (Throwable $e) {
            Logger::logException($e, Logger::LEVEL_ERROR);
        }
    }

    public function answerCallbackQuery(
        string $callbackQueryId,
        ?string $text = null,
        ?bool $showAlert = null,
        ?string $url = null,
        ?int $cacheTime = null
    ): void {
        try {
            $this->telegramFacade->sendTelegramRequest([
                'token' => $this->token,
                'method' => 'answerCallbackQuery',
                'callback_query_id' => $callbackQueryId,
                'text' => $text,
                'show_alert' => $showAlert,
                'url' => $url,
                'cache_time' => $cacheTime,
            ]);
        } catch (Throwable $e) {
            Logger::logException($e, Logger::LEVEL_ERROR);
        }
    }

    public function sendPhoto(
        TelegramFile $telegramFile,
        TelegramChat $telegramChat,
        ?ReplyMarkup $replyMarkup = null,
        ?string $parseMode = null,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?int $replyToMessageId = null,
        ?bool $allowSendingWithoutReply = null
    ): void {
        $this->telegramFacade->sendTelegramRequest([
            'token' => $this->token,
            'method' => 'sendPhoto',
            'chat_id' => $telegramChat->getId(),
            'photo' => $telegramFile->getFileID(),
            'caption' => $telegramFile->getCaption(),
            'parse_mode' => $parseMode,
            'disable_notification' => $disableNotification,
            'protect_content' => $protectContent,
            'reply_to_message_id' => $replyToMessageId,
            'allow_sending_without_reply' => $allowSendingWithoutReply,
            'reply_markup' => $replyMarkup ? json_encode($replyMarkup, JSON_THROW_ON_ERROR) : null,
        ]);
    }

    public function answerInlineQuery(
        string $inlineQueryId,
        array $results,
        ?int $cacheTime = null,
        ?bool $isPersonal = null,
        ?string $nextOffset = null,
    ): void {
        $this->telegramFacade->sendTelegramRequest([
            'token' => $this->token,
            'method' => 'answerInlineQuery',
            'inline_query_id' => $inlineQueryId,
            'cache_time' => $cacheTime,
            'is_personal' => $isPersonal,
            'next_offset' => $nextOffset,
            'results' => json_encode($results),
        ]);
    }
}