<?php

namespace App;

use App\Config\Config;
use Boot\Classes\MethodOptionalFields;
use Boot\Facades\TelegramFacade;
use Boot\Src\Entities\InputFile;
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

    /**
     * @see TelegramFacade::sendPhoto()
     * For more details.
     */
    public function sendPhoto(
        string $chatId,
        InputFile|string $photo,
        ?string $caption = null,
        ?ReplyMarkup $replyMarkup = null,
        ?string $parseMode = null,
        bool $hasSpoiler = false,
        ?MethodOptionalFields $optionalFields = null
    ): TelegramMessage {
        return $this
            ->telegramFacade
            ->sendPhoto($this->token, ...func_get_args());
    }

    /**
     * @see TelegramFacade::sendVideo()
     * For more details.
     */
    public function sendVideo(
        string $chatId,
        InputFile|string $video,
        InputFile|null $thumbnail = null,
        ?ReplyMarkup $replyMarkup = null,
        ?int $duration = null,
        ?int $width = null,
        ?int $height = null,
        ?string $caption = null,
        ?array $captionEntities = null,
        ?string $parseMode = null,
        bool $hasSpoiler = false,
        bool $supportsStreaming = false,
        ?MethodOptionalFields $optionalFields = null,
    ): TelegramMessage {
        return $this
            ->telegramFacade
            ->sendVideo($this->token, ...func_get_args());
    }

    /**
     * @see TelegramFacade::sendAudio()
     * For more details.
     */
    public function sendAudio(
        string $chatId,
        InputFile|string $audio,
        InputFile|null $thumbnail = null,
        ?string $caption = null,
        ?array $captionEntities = null,
        ?string $parseMode = null,
        ?int $duration = null,
        ?string $performer = null,
        ?string $title = null,
        ?ReplyMarkup $replyMarkup = null,
        ?MethodOptionalFields $optionalFields = null,
    ): TelegramMessage {
        return $this
            ->telegramFacade
            ->sendAudio($this->token, ...func_get_args());
    }

    /**
     * @see TelegramFacade::sendVoice()
     * For more details.
     */
    public function sendVoice(
        string $chatId,
        InputFile|string $voice,
        ?string $caption = null,
        ?array $captionEntities = null,
        ?string $parseMode = null,
        ?int $duration = null,
        ?ReplyMarkup $replyMarkup = null,
        ?MethodOptionalFields $optionalFields = null,
    ): TelegramMessage {
        return $this
            ->telegramFacade
            ->sendVoice($this->token, ...func_get_args());
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
        ?string $nextOffset = null
    ): void {
        $this->telegramFacade->answerInlineQuery(
            $this->token,
            ...func_get_args()
        );
    }

    public function setWebhook(
        ?string $ipAddress = null,
        ?int $maxConnections = null,
        ?array $allowUpdates = null,
        ?bool $dropPendingUpdates = null,
        ?string $secretToken = null
    ): void {
        $this->telegramFacade->setWebhook(
            $this->token,
            'https://' . Config::bot()['bot_url'],
            ...func_get_args(),
        );
    }

    public function deleteWebhook(
        bool $dropPendingUpdates = false
    ): void {
        $this->telegramFacade->deleteWebhook(
            $this->token,
            $dropPendingUpdates
        );
    }
}