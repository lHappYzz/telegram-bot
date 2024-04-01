<?php

namespace Boot\Src\Entities;

use App\States\DefaultState;
use App\States\NoState;
use Boot\Application;
use Boot\Responsibilities;
use Boot\Interfaces\MessageableEntity;
use Boot\Src\Abstracts\BaseCommand;
use Boot\Src\Abstracts\UpdateUnit;
use Boot\Src\Entities\ReplyMarkup\InlineKeyboardMarkup;
use Boot\Src\PhotoSize;

class TelegramMessage extends UpdateUnit implements MessageableEntity
{
    /** @var BaseCommand|null */
    private ?BaseCommand $command = null;

    private bool $hasFile = false;
    private ?string $fileType = null;

    /**
     * @param int $messageId
     * @param TelegramChat $chat
     * @param int $date
     * @param TelegramUser|null $from
     * @param string|null $text
     * @param TelegramMessage|null $replyToMessage
     * @param InlineKeyboardMarkup|null $replyMarkup
     * @param MessageEntity[]|null $entities
     * @param PhotoSize[]|null $photo
     */
    public function __construct(
        protected int $messageId,
        protected TelegramChat $chat,
        protected int $date,
        protected ?TelegramUser $from = null,
        protected ?string $text = null,
        protected ?TelegramMessage $replyToMessage = null,
        protected ?InlineKeyboardMarkup $replyMarkup = null,
        protected ?array $entities = null,
        protected ?array $photo = null,
    ) {
        $this->setCommand();
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

    public function getMessageDate($format = 'Y-m-d H:i:s'): string
    {
        return date($format, $this->date);
    }

    /**
     * @return BaseCommand|null
     */
    public function getCommand(): ?BaseCommand
    {
        return $this->command;
    }

    /**
     * @return TelegramMessage|null
     */
    public function getRepliedMessage(): ?TelegramMessage
    {
        return $this->replyToMessage;
    }

    /**
     * @return InlineKeyboardMarkup|null
     */
    public function getReplyMarkup(): ?InlineKeyboardMarkup
    {
        return $this->replyMarkup;
    }

    /**
     * @return bool
     */
    public function isCommand(): bool
    {
        return isset($this->command);
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
            ($this->getChat()->getChatState() instanceof DefaultState ||
            $this->getChat()->getChatState() instanceof NoState) &&
            $this->isCommand()
        ) {
            $responsibility->handleCommand($this);
            return;
        }
        $responsibility->handleTelegramChatState($this);
    }

    /**
     * @return void
     */
    private function setCommand(): void
    {
        foreach ($this->entities as $messageEntity) {
            if (
                $messageEntity->getType() === MessageEntity::BOT_COMMAND_TYPE &&
                $messageEntity->getOffset() === 0
            ) {
                $this->command = container(Application::class)
                    ->getCommand(
                        substr(
                            $this->text,
                            $messageEntity->getOffset(),
                            $messageEntity->getLength()
                        )
                    );
                return;
            }
        }
        $this->command = null;
    }
}