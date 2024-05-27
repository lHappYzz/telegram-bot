<?php

namespace Boot\Facades;

use Boot\Classes\MethodOptionalFields;
use Boot\Log\Logger;
use Boot\Src\Abstracts\Telegram;
use Boot\Src\APIMethods\AnswerCallbackQuery;
use Boot\Src\APIMethods\AnswerInlineQuery;
use Boot\Src\APIMethods\DeleteWebhook;
use Boot\Src\APIMethods\EditInlineMessageText;
use Boot\Src\APIMethods\EditMessageText;
use Boot\Src\APIMethods\SendAudio;
use Boot\Src\APIMethods\SendDocument;
use Boot\Src\APIMethods\SendMessage;
use Boot\Src\APIMethods\SendPhoto;
use Boot\Src\APIMethods\SendVideo;
use Boot\Src\APIMethods\SendVideoNote;
use Boot\Src\APIMethods\SendVoice;
use Boot\Src\APIMethods\SetWebhook;
use Boot\Src\Entities\InlineMode\InlineQueryResult;
use Boot\Src\Entities\InputFile;
use Boot\Src\Entities\MessageEntity;
use Boot\Src\Entities\ReplyMarkup\ReplyMarkup;
use Boot\Src\Entities\TelegramMessage;
use Boot\Src\Exceptions\Request\TelegramRequestException;
use RuntimeException;

class TelegramFacade extends Telegram
{
    /**
     * Use this method to send text messages. On success, the sent Message is returned.
     * @link https://core.telegram.org/bots/api#sendmessage
     * @param string $text
     * @param string $chatId
     * @param ReplyMarkup|null $replyMarkup
     * @param string|null $parseMode
     * @param bool $disableWebPagePreview
     * @param MethodOptionalFields|null $optionalFields
     * @return TelegramMessage
     */
    public function sendMessage(
        string $text,
        string $chatId,
        ?ReplyMarkup $replyMarkup = null,
        ?string $parseMode = null,
        bool $disableWebPagePreview = false,
        ?MethodOptionalFields $optionalFields = null
    ): TelegramMessage {
        try {
            $response = $this->request->prepare(new SendMessage(
                $text, $chatId, $replyMarkup, $parseMode, $disableWebPagePreview, $optionalFields
            ))->send();
        } catch (TelegramRequestException $e) {
            Logger::logException($e, Logger::LEVEL_ERROR);
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        return $response->createMessage();
    }

    /**
     * Use this method to send photos. On success, the sent Message is returned.
     * @link https://core.telegram.org/bots/api#sendphoto
     * @param string $chatId
     * @param InputFile|string $photo
     * Use InputFile for uploading file with multipart/form-data or pass string that contains
     * URL(telegram will download file) or file_id (if file already uploaded to telegram)
     * @param string|null $caption
     * @param ReplyMarkup|null $replyMarkup
     * @param string|null $parseMode
     * @param bool $hasSpoiler
     * @param MethodOptionalFields|null $optionalFields
     * @return TelegramMessage
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
        try {
            $response = $this->request->prepare(new SendPhoto(
                $chatId, $photo, $caption, $replyMarkup, $parseMode, $hasSpoiler, $optionalFields
            ))->send();
        } catch (TelegramRequestException $e) {
            Logger::logException($e, Logger::LEVEL_ERROR);
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        return $response->createMessage();
    }

    /**
     * @link https://core.telegram.org/bots/api#sendvideo
     * @param string $chatId
     * @param InputFile|string $video
     * Use InputFile for uploading file with multipart/form-data or pass string that contains
     * URL(telegram will download file) or file_id (if file already uploaded to telegram)
     * @param InputFile|null $thumbnail
     * Use InputFile for uploading file with multipart/form-data
     * @param ReplyMarkup|null $replyMarkup
     * @param int|null $duration
     * @param int|null $width
     * @param int|null $height
     * @param string|null $caption
     * @param array|null $captionEntities
     * @param string|null $parseMode
     * @param bool $hasSpoiler
     * @param bool $supportsStreaming
     * @param MethodOptionalFields|null $optionalFields
     * @return TelegramMessage
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
        try {
            $response = $this->request->prepare(new SendVideo(
                $chatId, $video, $thumbnail, $replyMarkup, $duration, $width, $height, $caption,
                $captionEntities, $parseMode, $hasSpoiler, $supportsStreaming, $optionalFields
            ))->send();
        } catch (TelegramRequestException $e) {
            Logger::logException($e, Logger::LEVEL_ERROR);
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        return $response->createMessage();
    }

    /**
     * @link https://core.telegram.org/bots/api#sendvideonote
     * @param string $chatId
     * @param InputFile|string $videoNote
     * Use InputFile for uploading file with multipart/form-data or pass string that contains
     * URL(telegram will download file) or file_id (if file already uploaded to telegram)
     * @param InputFile|null $thumbnail
     * Use InputFile for uploading file with multipart/form-data
     * @param ReplyMarkup|null $replyMarkup
     * @param int|null $duration
     * @param int|null $length
     * @param MethodOptionalFields|null $optionalFields
     * @return TelegramMessage
     */
    public function sendVideoNote(
        string $chatId,
        InputFile|string $videoNote,
        ?InputFile $thumbnail = null,
        ?ReplyMarkup $replyMarkup = null,
        ?int $duration = null,
        ?int $length = null,
        ?MethodOptionalFields $optionalFields = null,
    ): TelegramMessage {
        try {
            $response = $this->request->prepare(new SendVideoNote(
                $chatId, $videoNote, $thumbnail, $replyMarkup, $duration, $length, $optionalFields
            ))->send();
        } catch (TelegramRequestException $e) {
            Logger::logException($e, Logger::LEVEL_ERROR);
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        return $response->createMessage();
    }

    /**
     * @param string $chatId
     * @param InputFile|string $audio
     * Use InputFile for uploading file with multipart/form-data or pass string that contains
     * URL(telegram will download image) or file_id (if image already uploaded to telegram)
     * @param InputFile|null $thumbnail
     * Use InputFile for uploading file with multipart/form-data
     * @param string|null $caption
     * @param array|null $captionEntities
     * @param string|null $parseMode
     * @param int|null $duration
     * @param string|null $performer
     * @param string|null $title
     * @param ReplyMarkup|null $replyMarkup
     * @param MethodOptionalFields|null $optionalFields
     * @return TelegramMessage
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
        try {
            $response = $this->request->prepare(new SendAudio(
                $chatId, $audio, $thumbnail, $caption, $captionEntities, $parseMode,
                $duration, $performer, $title, $replyMarkup, $optionalFields
            ))->send();
        } catch (TelegramRequestException $e) {
            Logger::logException($e, Logger::LEVEL_ERROR);
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        return $response->createMessage();
    }

    /**
     * @param string $chatId
     * @param InputFile|string $voice
     * Use InputFile for uploading file with multipart/form-data or pass string that contains
     * URL(telegram will download image) or file_id (if image already uploaded to telegram)
     * @param string|null $caption
     * @param array|null $captionEntities
     * @param string|null $parseMode
     * @param int|null $duration
     * @param ReplyMarkup|null $replyMarkup
     * @param MethodOptionalFields|null $optionalFields
     * @return TelegramMessage
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
        try {
            $response = $this->request->prepare(new SendVoice(
                $chatId, $voice, $caption, $captionEntities, $parseMode, $duration, $replyMarkup, $optionalFields
            ))->send();
        } catch (TelegramRequestException $e) {
            Logger::logException($e, Logger::LEVEL_ERROR);
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        return $response->createMessage();
    }

    /**
     * @param string $chatId
     * @param InputFile|string $document
     * Use InputFile for uploading file with multipart/form-data or pass string that contains
     * URL(telegram will download image) or file_id (if image already uploaded to telegram)
     * @param InputFile|null $thumbnail
     * Use InputFile for uploading file with multipart/form-data
     * @param string|null $caption
     * @param string|null $parseMode
     * @param array|null $captionEntities
     * @param bool $disableContentTypeDetection
     * @param ReplyMarkup|null $replyMarkup
     * @param MethodOptionalFields|null $optionalFields
     * @return TelegramMessage
     */
    public function sendDocument(
        string $chatId,
        InputFile|string $document,
        ?InputFile $thumbnail = null,
        ?string $caption = null,
        ?string $parseMode = null,
        ?array $captionEntities = null,
        bool $disableContentTypeDetection = false,
        ?ReplyMarkup $replyMarkup = null,
        ?MethodOptionalFields $optionalFields = null,
    ): TelegramMessage {
        try {
            $response = $this->request->prepare(new SendDocument(
                $chatId, $document, $thumbnail, $caption, $parseMode, $captionEntities,
                $disableContentTypeDetection, $replyMarkup, $optionalFields
            ))->send();
        } catch (TelegramRequestException $e) {
            Logger::logException($e, Logger::LEVEL_ERROR);
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        return $response->createMessage();
    }

    /**
     * Use this method to edit text and game messages. On success the edited Message is returned.
     * @link https://core.telegram.org/bots/api#sendmessage
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
        string $text,
        string $chatId,
        int $messageId,
        ?ReplyMarkup $replyMarkup = null,
        ?string $parseMode = null,
        bool $disableWebPagePreview = false,
        ?array $entities = null
    ): TelegramMessage {
        try {
            $response = $this->request->prepare(new EditMessageText(
                $text, $chatId, $messageId, $replyMarkup, $parseMode, $disableWebPagePreview, $entities
            ))->send();
        } catch (TelegramRequestException $e) {
            Logger::logException($e, Logger::LEVEL_ERROR);
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        return $response->createMessage();
    }

    /**
     * Use this method to edit inline message text.
     * @link https://core.telegram.org/bots/api#sendmessage
     * @param string $text
     * @param int $inlineMessageId
     * @param ReplyMarkup|null $replyMarkup
     * @param string|null $parseMode
     * @param bool $disableWebPagePreview
     * @param MessageEntity[]|null $entities
     */
    public function editInlineMessageText(
        string $text,
        int $inlineMessageId,
        ?ReplyMarkup $replyMarkup = null,
        ?string $parseMode = null,
        bool $disableWebPagePreview = false,
        ?array $entities = null
    ): void {
        try {
            $this->request->prepare(new EditInlineMessageText(
                $text, $inlineMessageId, $replyMarkup, $parseMode, $disableWebPagePreview, $entities
            ))->send();
        } catch (TelegramRequestException $e) {
            Logger::logException($e, Logger::LEVEL_ERROR);
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Use this method to send answers to callback queries sent from inline keyboards.
     * The answer will be displayed to the user as a notification at the top of the chat screen or as an alert.
     * @link https://core.telegram.org/bots/api#answercallbackquery
     * @param string $callbackQueryId
     * @param string|null $text
     * @param bool|null $showAlert
     * @param string|null $url
     * @param int|null $cacheTime
     * @return void
     */
    public function answerCallbackQuery(
        string $callbackQueryId,
        ?string $text = null,
        ?bool $showAlert = null,
        ?string $url = null,
        ?int $cacheTime = null
    ): void {
        try {
            $this->request->prepare(new AnswerCallbackQuery(
                $callbackQueryId, $text, $showAlert, $url, $cacheTime
            ))->send();
        } catch (TelegramRequestException $e) {
            Logger::logException($e, Logger::LEVEL_ERROR);
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Use this method to send answers to an inline query.
     * No more than 50 results per query are allowed.
     * @link https://core.telegram.org/bots/api#answerinlinequery
     * @param string $inlineQueryId
     * @param InlineQueryResult[] $results
     * @param int|null $cacheTime
     * @param bool|null $isPersonal
     * @param string|null $nextOffset
     * @return void
     */
    public function answerInlineQuery(
        string $inlineQueryId,
        array $results,
        ?int $cacheTime = null,
        ?bool $isPersonal = null,
        ?string $nextOffset = null,
    ): void {
        try {
            $this->request->prepare(new AnswerInlineQuery(
                $inlineQueryId, $results, $cacheTime, $isPersonal, $nextOffset
            ))->send();
        } catch (TelegramRequestException $e) {
            Logger::logException($e, Logger::LEVEL_ERROR);
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Use this method to specify a URL and receive incoming updates via an outgoing webhook.
     * Whenever there is an update for the bot, we will send an HTTPS POST request to the specified URL,
     * containing a JSON-serialized Update. In case of an unsuccessful request, we will give up after a reasonable
     * amount of attempts. Returns True on success.
     *
     * If you'd like to make sure that the webhook was set by you, you can specify secret data in the parameter
     * secret_token. If specified, the request will contain a header “X-Telegram-Bot-Api-Secret-Token” with the
     * secret token as content.
     * @link https://core.telegram.org/bots/api#setwebhook
     * @param string $url
     * @param string|null $ipAddress
     * @param int|null $maxConnections
     * @param array|null $allowUpdates
     * @param bool|null $dropPendingUpdates
     * @param string|null $secretToken
     * @return void
     */
    public function setWebhook(
        string $url,
        ?string $ipAddress = null,
        ?int $maxConnections = null,
        ?array $allowUpdates = null,
        bool $dropPendingUpdates = false,
        ?string $secretToken = null,
    ): void {
        try {
            $this->request->prepare(new SetWebhook(
                $url, $ipAddress, $maxConnections, $allowUpdates, $dropPendingUpdates, $secretToken
            ))->send();
        } catch (TelegramRequestException $e) {
            Logger::logException($e, Logger::LEVEL_ERROR);
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @link https://core.telegram.org/bots/api#deletewebhook
     * @param bool $dropPendingUpdates
     * @return void
     */
    public function deleteWebhook(
        bool $dropPendingUpdates = false,
    ): void {
        try {
            $this->request->prepare(new DeleteWebhook(
                $dropPendingUpdates
            ))->send();
        } catch (TelegramRequestException $e) {
            Logger::logException($e, Logger::LEVEL_ERROR);
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }
}