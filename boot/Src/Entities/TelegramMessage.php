<?php

namespace Boot\Src\Entities;

use App\States\DefaultState;
use App\States\NoState;
use Boot\Responsibilities;
use Boot\Interfaces\MessageableEntity;
use Boot\Src\Abstracts\Telegram;
use Boot\Src\Abstracts\UpdateUnit;
use Boot\Src\Entities\ReplyMarkup\InlineKeyboardMarkup;
use Boot\Src\PhotoSize;
use JetBrains\PhpStorm\Pure;

class TelegramMessage extends UpdateUnit implements MessageableEntity
{

    /** @var string */
    private string $commandClassName;

    private bool $hasFile = false;
    private ?string $fileType = null;

    public function __construct(
        protected int $messageId,
        protected TelegramChat $chat,
        protected int $date,
        protected ?TelegramUser $from = null,
        protected ?string $text = null,
        protected ?TelegramMessage $replyToMessage = null,
        protected ?InlineKeyboardMarkup $replyMarkup = null,
        /** @var PhotoSize[] */
        protected ?array $photo = null,
    ) {
        $this->setCommandClassName();
    }

    public function getMessageId(): int
    {
        return $this->messageId;
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

    public function isCommand(): bool
    {
        return ($this->text[0] === '/') && !str_contains($this->text, ' ');
    }

    /**
     * @return InlineKeyboardMarkup|null
     */
    public function getReplyMarkup(): ?InlineKeyboardMarkup
    {
        return $this->replyMarkup;
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

    private function setCommandClassName(): void
    {
        if ($this->isCommand()) {
            $this->commandClassName = Telegram::COMMANDS_NAMESPACE . ucfirst(str_replace('/', '', $this->text)) . 'Command';
        } else {
            $this->commandClassName = '';
        }
    }
}