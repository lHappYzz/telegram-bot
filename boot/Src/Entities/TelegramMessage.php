<?php

namespace Boot\Src\Entities;

use App\States\DefaultState;
use App\States\NoState;
use Boot\Responsibilities;
use Boot\Interfaces\MessageableEntity;
use Boot\Src\Abstracts\Telegram;
use Boot\Src\Abstracts\UpdateUnit;
use Boot\Src\ReplyMarkup\InlineKeyboardButton;
use Boot\Src\ReplyMarkup\InlineKeyboardMarkup;
use Boot\Src\TelegramFile;
use Boot\Src\TelegramPhoto;
use Boot\Src\TelegramVideo;
use Boot\Traits\Helpers;
use JetBrains\PhpStorm\Pure;

class TelegramMessage extends UpdateUnit implements MessageableEntity
{
    use Helpers;

    private int $messageID;
    private TelegramUser $from;
    private TelegramChat $chat;
    private int $date;
    private ?string $text;

    /** @var ?TelegramFile File that comes with message */
    private ?TelegramFile $telegramFile;

    private string $commandClassName;
    private TelegramMessage $replyToMessage;
    private ?InlineKeyboardMarkup $inlineKeyboardMarkup;

    private bool $hasFile = false;
    private ?string $fileType = null;

    public function __construct(array $messageData)
    {
        $this->messageID = $messageData['message_id'];

        $this->from = new TelegramUser($messageData['from']);
        $this->chat = new TelegramChat($messageData['chat']);

        $this->date = $messageData['date'];
        $this->text = $messageData['text'];

        if (array_key_exists('reply_markup', $messageData)) {
            $this->setInlineKeyboardMarkup($messageData['reply_markup']);
        }

        $this->setTelegramFile($messageData);
        $this->setCommandClassName();
        $this->setReplyToMessage($messageData);
    }

    public function getMessageID(): int
    {
        return $this->messageID;
    }

    public function getFrom(): TelegramUser
    {
        return $this->from;
    }

    public function getChat(): TelegramChat
    {
        return $this->chat;
    }

    public function getMessageText(): ?string
    {
        return $this->text;
    }

    #[Pure] public function getMessageDate($format = 'Y-m-d H:i:s'): string
    {
        return date($format, $this->date);
    }

    public function getCommandClassName(): string
    {
        return $this->commandClassName;
    }

    public function getTelegramFile(): ?TelegramFile
    {
        return $this->telegramFile;
    }

    /**
     * Get message that was replied otherwise null is returned,
     * so always check your var for not being null
     *
     * @return ?TelegramMessage
     */
    public function getRepliedMessage(): ?TelegramMessage
    {
        return $this->replyToMessage ?? null;
    }

    public function getInlineKeyboardMarkup(): ?InlineKeyboardMarkup
    {
        return $this->inlineKeyboardMarkup;
    }

    public function isCommand(): bool
    {
        return ($this->text[0] === '/') && !str_contains($this->text, ' ');
    }

    /**
     * Method describes how and when the responsible code base should run for each of update unit
     *
     * @param Responsibilities $responsibility
     * @return void
     */
    public function responsibilize(Responsibilities $responsibility): void
    {
        if (
            $this->getChat()->getChatState() instanceof DefaultState ||
            $this->getChat()->getChatState() instanceof NoState
        ) {
            if ($this->isCommand()) {
                $responsibility->handleCommand($this);
            }
        } else {
            $responsibility->handleTelegramChatState($this);
        }
    }

    private function setTelegramFile(array $messageData): void
    {
        if ($photoExists = array_key_exists(TelegramFile::MESSAGE_FILE_PHOTO, $messageData)) {
            $this->telegramFile = new TelegramPhoto($this->arrayLast($messageData[TelegramFile::MESSAGE_FILE_PHOTO]), $messageData['caption']);
        }

        if ($videoExists = array_key_exists(TelegramFile::MESSAGE_FILE_VIDEO, $messageData)) {
            $this->telegramFile = new TelegramVideo($messageData[TelegramFile::MESSAGE_FILE_VIDEO], $messageData['caption']);
        }

        if (!$photoExists && !$videoExists) {
            $this->telegramFile = null;
        }
    }

    private function setReplyToMessage($messageData): void
    {
        if (array_key_exists('reply_to_message', $messageData)) {
            $this->replyToMessage = new TelegramMessage($messageData['reply_to_message']);
        }
    }

    private function setCommandClassName(): void
    {
        if ($this->isCommand()) {
            $this->commandClassName = Telegram::COMMANDS_NAMESPACE . ucfirst(str_replace('/', '', $this->text)) . 'Command';
        } else {
            $this->commandClassName = '';
        }
    }

    private function setInlineKeyboardMarkup(array $replyMarkup): void
    {
        $inlineKeyboardMarkup = new InlineKeyboardMarkup();
        foreach ($replyMarkup['inline_keyboard'] as $keyboardRow) {
            $inlineKeyboardRow = $inlineKeyboardMarkup->addKeyboardRow();
            foreach ($keyboardRow as $rowButton) {
                $settings = [];
                $settings[] = $this->resolveCallbackQueryHandlerName($rowButton['callback_data']);
                $settings[] = $this->arrayLast(explode(InlineKeyboardButton::CALLBACK_DATA_DELIMITER, $rowButton['callback_data']));

                $inlineKeyboardRow->addButton($rowButton['text'], $settings);
            }
        }
        $this->inlineKeyboardMarkup = $inlineKeyboardMarkup;
    }
}