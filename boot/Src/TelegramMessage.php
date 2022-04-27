<?php

namespace Boot\Src;

use Boot\Src\ReplyMarkup\InlineKeyboardButton;
use Boot\Src\ReplyMarkup\InlineKeyboardMarkup;
use Boot\Traits\Helpers;
use JetBrains\PhpStorm\Pure;

class TelegramMessage extends Entity
{
    use Helpers;

    private int $messageID;
    private TelegramUser $from;
    private TelegramChat $chat;

    private int $date;
    private string $text;
    private string $commandClassName;
    private TelegramMessage $replyToMessage;
    private array $photo = [];
    private string $caption = '';
    private ?InlineKeyboardMarkup $inlineKeyboardMarkup;

    public function __construct($messageData)
    {
        $this->messageID = $messageData['message_id'];

        $this->from = new TelegramUser($messageData['from']);
        $this->chat = new TelegramChat($messageData['chat']);

        $this->date = $messageData['date'];
        $this->text = $messageData['text'];

        if (array_key_exists('reply_markup', $messageData)) {
            $this->setInlineKeyboardMarkup($messageData['reply_markup']);
        }

        if (array_key_exists('photo', $messageData)) {
            if (array_key_exists('caption', $messageData)) {
                $this->caption = $messageData['caption'];
            }
            foreach ($messageData['photo'] as $photoData) {
                $this->photo[] = new TelegramPhotoSize($photoData);
            }
        }

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

    public function getMessageText(): string
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

    /**
     * Returns array of telegramPhotoSize object.
     * Read more: https://core.telegram.org/bots/api#photosize
     * @return array
     */
    public function getPhoto(): array
    {
        return $this->photo;
    }

    public function getCaption(): string
    {
        return $this->caption;
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