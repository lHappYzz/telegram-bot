<?php

namespace Boot\Facades;

use Boot\Classes\MethodOptionalFields;
use Boot\Src\Abstracts\Telegram;
use Boot\Src\Entities\InlineMode\InlineQueryResult;
use Boot\Src\Entities\MessageEntity;
use Boot\Src\Entities\ReplyMarkup\ReplyMarkup;
use Boot\Src\Entities\TelegramMessage;

class TelegramFacade extends Telegram
{
    /**
     * Use this method to send text messages. On success, the sent Message is returned.
     * @link https://core.telegram.org/bots/api#sendmessage
     * @param string $token
     * @param string $text
     * @param string $chatId
     * @param ReplyMarkup|null $replyMarkup
     * @param string|null $parseMode
     * @param bool $disableWebPagePreview
     * @param MethodOptionalFields|null $optionalFields
     * @return TelegramMessage
     *
     */
    public function sendMessage(
        string $token,
        string $text,
        string $chatId,
        ?ReplyMarkup $replyMarkup = null,
        ?string $parseMode = null,
        bool $disableWebPagePreview = false,
        ?MethodOptionalFields $optionalFields = null
    ): TelegramMessage {
        $response = $this->sendTelegramRequest([
            'token' => $token,
            'method' => 'sendMessage',
            'text' => $text,
            'chat_id' => $chatId,
            'parse_mode' => $parseMode,
            'disable_web_page_preview' => $disableWebPagePreview,
            'reply_markup' => $replyMarkup ? json_encode($replyMarkup) : null,
            ... is_null($optionalFields) ? [] : $optionalFields?->jsonSerialize(),
        ]);

        return $response->createMessage();
    }

    /**
     * Use this method to edit text and game messages. On success the edited Message is returned.
     * @link https://core.telegram.org/bots/api#sendmessage
     * @param string $token
     * @param string $text
     * @param string $chatId
     * @param int $messageId
     * @param ReplyMarkup|null $replyMarkup
     * @param string|null $parseMode
     * @param bool $disableWebPagePreview
     * @param MessageEntity[]|null $entities
     * @return TelegramMessage
     */
    public function editMessageText(
        string $token,
        string $text,
        string $chatId,
        int $messageId,
        ?ReplyMarkup $replyMarkup = null,
        ?string $parseMode = null,
        bool $disableWebPagePreview = false,
        ?array $entities = null
    ): TelegramMessage {
        $response = $this->sendTelegramRequest([
            'token' => $token,
            'method' => 'editMessageText',
            'text' => $text,
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'parse_mode' => $parseMode,
            'disable_web_page_preview' => $disableWebPagePreview,
            'reply_markup' => $replyMarkup ? json_encode($replyMarkup) : null,
            'entities' => $entities ? json_encode($entities) : null,
        ]);

        return $response->createMessage();
    }

    /**
     * Use this method to edit inline message text.
     * @link https://core.telegram.org/bots/api#sendmessage
     * @param string $token
     * @param string $text
     * @param int $inlineMessageId
     * @param ReplyMarkup|null $replyMarkup
     * @param string|null $parseMode
     * @param bool $disableWebPagePreview
     * @param MessageEntity[]|null $entities
     */
    public function editInlineMessageText(
        string $token,
        string $text,
        int $inlineMessageId,
        ?ReplyMarkup $replyMarkup = null,
        ?string $parseMode = null,
        bool $disableWebPagePreview = false,
        ?array $entities = null
    ): void {
        $this->sendTelegramRequest([
            'token' => $token,
            'method' => 'editMessageText',
            'text' => $text,
            'inline_message_id' => $inlineMessageId,
            'parse_mode' => $parseMode,
            'disable_web_page_preview' => $disableWebPagePreview,
            'reply_markup' => $replyMarkup ? json_encode($replyMarkup) : null,
            'entities' => $entities ? json_encode($entities) : null,
        ]);
    }

    /**
     * Use this method to send answers to callback queries sent from inline keyboards.
     * The answer will be displayed to the user as a notification at the top of the chat screen or as an alert.
     * @link https://core.telegram.org/bots/api#answercallbackquery
     * @param string $token
     * @param string $callbackQueryId
     * @param string|null $text
     * @param bool|null $showAlert
     * @param string|null $url
     * @param int|null $cacheTime
     * @return void
     */
    public function answerCallbackQuery(
        string $token,
        string $callbackQueryId,
        ?string $text = null,
        ?bool $showAlert = null,
        ?string $url = null,
        ?int $cacheTime = null
    ): void {
        $this->sendTelegramRequest([
            'token' => $token,
            'method' => 'answerCallbackQuery',
            'callback_query_id' => $callbackQueryId,
            'text' => $text,
            'show_alert' => $showAlert,
            'url' => $url,
            'cache_time' => $cacheTime,
        ]);
    }

    /**
     * Use this method to send answers to an inline query.
     * No more than 50 results per query are allowed.
     * @link https://core.telegram.org/bots/api#answercallbackquery
     * @param string $token
     * @param string $inlineQueryId
     * @param InlineQueryResult[] $results
     * @param int|null $cacheTime
     * @param bool|null $isPersonal
     * @param string|null $nextOffset
     * @return void
     */
    public function answerInlineQuery(
        string $token,
        string $inlineQueryId,
        array $results,
        ?int $cacheTime = null,
        ?bool $isPersonal = null,
        ?string $nextOffset = null,
    ): void {
        $this->sendTelegramRequest([
            'token' => $token,
            'method' => 'answerInlineQuery',
            'inline_query_id' => $inlineQueryId,
            'cache_time' => $cacheTime,
            'is_personal' => $isPersonal,
            'next_offset' => $nextOffset,
            'results' => json_encode($results),
        ]);
    }
}