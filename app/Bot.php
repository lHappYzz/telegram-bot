<?php

namespace App;

use Boot\Classes\MethodOptionalFields;
use Boot\Facades\TelegramFacade;
use Boot\Src\Entities\File;
use Boot\Src\Entities\InputFile;
use Boot\Src\Entities\ReplyMarkup\ReplyMarkup;
use Boot\Src\Entities\TelegramMessage;
use Boot\Traits\DynamicTelegramCall;

/**
 * @method TelegramMessage sendMessage(string $text, string $chatId, ?ReplyMarkup $replyMarkup = null, ?string $parseMode = null, bool $disableWebPagePreview = false, ?MethodOptionalFields $optionalFields = null);
 * @method TelegramMessage sendPhoto(string $chatId, InputFile|string $photo, ?string $caption = null, ?ReplyMarkup $replyMarkup = null, ?string $parseMode = null, bool $hasSpoiler = false, ?MethodOptionalFields $optionalFields = null);
 * @method TelegramMessage sendVideo(string $chatId, InputFile|string $video, InputFile|null $thumbnail = null, ?ReplyMarkup $replyMarkup = null, ?int $duration = null, ?int $width = null, ?int $height = null, ?string $caption = null, ?array $captionEntities = null, ?string $parseMode = null, bool $hasSpoiler = false, bool $supportsStreaming = false, ?MethodOptionalFields $optionalFields = null);
 * @method TelegramMessage sendVideoNote(string $chatId, InputFile|string $videoNote, ?InputFile $thumbnail = null, ?ReplyMarkup $replyMarkup = null, ?int $duration = null, ?int $length = null, ?MethodOptionalFields $optionalFields = null);
 * @method TelegramMessage sendAudio(string $chatId, InputFile|string $audio, InputFile|null $thumbnail = null, ?string $caption = null, ?array $captionEntities = null, ?string $parseMode = null, ?int $duration = null, ?string $performer = null, ?string $title = null, ?ReplyMarkup $replyMarkup = null, ?MethodOptionalFields $optionalFields = null);
 * @method TelegramMessage sendVoice(string $chatId, InputFile|string $voice, ?string $caption = null, ?array $captionEntities = null, ?string $parseMode = null, ?int $duration = null, ?ReplyMarkup $replyMarkup = null, ?MethodOptionalFields $optionalFields = null);
 * @method TelegramMessage sendDocument(string $chatId, InputFile|string $document, ?InputFile $thumbnail = null, ?string $caption = null, ?string $parseMode = null, ?array $captionEntities = null, bool $disableContentTypeDetection = false, ?ReplyMarkup $replyMarkup = null, ?MethodOptionalFields $optionalFields = null);
 * @method TelegramMessage sendAnimation(string $chatId, InputFile|string $animation, InputFile|null $thumbnail = null, ?int $duration = null, ?int $width = null, ?int $height = null, ?string $caption = null, ?string $parseMode = null, ?array $captionEntities = null, bool $showCaptionAboveMedia = false, bool $hasSpoiler = false, ?ReplyMarkup $replyMarkup = null, ?MethodOptionalFields $optionalFields = null);
 * @method TelegramMessage[] sendMediaGroup(string $chatId, array $media, ?MethodOptionalFields $optionalFields = null);
 * @method File getFile(string $fileId);
 * @method TelegramMessage editMessageText(string $text, string $chatId, int $messageId, ?ReplyMarkup $replyMarkup = null, ?string $parseMode = null, bool $disableWebPagePreview = false);
 * @method void answerCallbackQuery(string $callbackQueryId, ?string $text = null, ?bool $showAlert = null, ?string $url = null, ?int $cacheTime = null);
 * @method void answerInlineQuery(string $inlineQueryId, array $results, ?int $cacheTime = null, ?bool $isPersonal = null, ?string $nextOffset = null);
 * @method void setWebhook(?string $ipAddress = null, ?int $maxConnections = null, ?array $allowUpdates = null, ?bool $dropPendingUpdates = null, ?string $secretToken = null);
 * @method void deleteWebhook(bool $dropPendingUpdates = false);
 */
class Bot
{
    use DynamicTelegramCall;

    /**
     * @param TelegramFacade $telegramFacade
     */
    public function __construct(
        TelegramFacade $telegramFacade,
    ) {
        $this->telegramFacade = $telegramFacade;
    }
}