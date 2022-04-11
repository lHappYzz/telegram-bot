<?php

namespace Boot\Src;

use Boot\Src\ReplyMarkup\InlineKeyboardMarkup;

class telegramMessage extends Entity
{
    private int $messageID;
    private telegramUser $from;
    private telegramChat $chat;

    private int $date;
    private string $text;
    private $commandClassName;
    private telegramMessage $replyToMessage;
    private array $photo = [];
    private string $caption = '';
    private ?InlineKeyboardMarkup $inlineKeyboardMarkup;

    public function __construct($messageData)
    {
        $this->messageID = $messageData['message_id'];

        $this->from = new telegramUser($messageData['from']);
        $this->chat = new telegramChat($messageData['chat']);

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
                $this->photo[] = new telegramPhotoSize($photoData);
            }
        }

        $this->setCommandClassName();

        $this->setReplyToMessage($messageData);
    }

    public function getMessageID(): int
    {
        return $this->messageID;
    }

    public function getFrom(): telegramUser
    {
        return $this->from;
    }

    public function getChat(): telegramChat
    {
        return $this->chat;
    }

    public function getMessageText(): string
    {
        return $this->text;
    }

    public function getMessageDate($format = 'Y-m-d H:i:s')
    {
        return date($format, $this->date);
    }

    public function getCommandClassName()
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
     * @return ?telegramMessage
     */
    public function getRepliedMessage(): ?telegramMessage
    {
        return $this->replyToMessage ?? null;
    }

    public function getInlineKeyboardMarkup(): ?InlineKeyboardMarkup
    {
        return $this->inlineKeyboardMarkup;
    }

    public function isCommand(): bool
    {
        if ($this->text[0] === '/') {
            if (strpos($this->text, ' ') === false) {
                return true;
            }
        }
        return false;
    }

    private function setReplyToMessage($messageData): void
    {
        if (array_key_exists('reply_to_message', $messageData)) {
            $this->replyToMessage = new telegramMessage($messageData['reply_to_message']);
        }
    }

    private function setCommandClassName(): void
    {
        if ($this->isCommand()) {
            $this->commandClassName = str_replace('/', '', $this->text) . 'Command';
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
                $inlineKeyboardRow->addButton($rowButton['text'], $rowButton['callback_data']);
            }
        }
        $this->inlineKeyboardMarkup = $inlineKeyboardMarkup;
    }


}