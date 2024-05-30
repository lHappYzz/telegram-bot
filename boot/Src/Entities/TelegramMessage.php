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

class TelegramMessage extends UpdateUnit implements MessageableEntity
{
    /** @var BaseCommand|null */
    private ?BaseCommand $command = null;

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
     * @param Video|null $video
     * @param VideoNote|null $videoNote
     * @param Audio|null $audio
     * @param Voice|null $voice
     * @param Document|null $document
     * @param string|null $mediaGroupId
     * @param bool|null $hasProtectedContent
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
        protected ?Video $video = null,
        protected ?VideoNote $videoNote = null,
        protected ?Audio $audio = null,
        protected ?Voice $voice = null,
        protected ?Document $document = null,
        protected ?string $mediaGroupId = null,
        protected ?bool $hasProtectedContent = null,
    ) {
        $this->setCommand();
    }

    /**
     * @return string|null
     */
    public function getMediaGroupId(): ?string
    {
        return $this->mediaGroupId;
    }

    /**
     * @return bool|null
     */
    public function getHasProtectedContent(): ?bool
    {
        return $this->hasProtectedContent;
    }

    /**
     * @return PhotoSize[]|null
     */
    public function getPhoto(): ?array
    {
        return $this->photo;
    }

    /**
     * @return VideoNote|null
     */
    public function getVideoNote(): ?VideoNote
    {
        return $this->videoNote;
    }

    /**
     * @return Voice|null
     */
    public function getVoice(): ?Voice
    {
        return $this->voice;
    }

    /**
     * @return Document|null
     */
    public function getDocument(): ?Document
    {
        return $this->document;
    }

    /**
     * @return int
     */
    public function getMessageId(): int
    {
        return $this->messageId;
    }

    /**
     * @return TelegramUser
     */
    public function getFrom(): TelegramUser
    {
        return $this->from;
    }

    /**
     * @return TelegramChat
     */
    public function getChat(): TelegramChat
    {
        return $this->chat;
    }

    /**
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @return BaseCommand|null
     */
    public function getCommand(): ?BaseCommand
    {
        return $this->command;
    }

    /**
     * @return InlineKeyboardMarkup|null
     */
    public function getReplyMarkup(): ?InlineKeyboardMarkup
    {
        return $this->replyMarkup;
    }

    /**
     * @return Video|null
     */
    public function getVideo(): ?Video
    {
        return $this->video;
    }

    /**
     * @return Audio|null
     */
    public function getAudio(): ?Audio
    {
        return $this->audio;
    }

    /**
     * @return int
     */
    public function getDate(): int
    {
        return $this->date;
    }

    /**
     * @return TelegramMessage|null
     */
    public function getReplyToMessage(): ?TelegramMessage
    {
        return $this->replyToMessage;
    }

    /**
     * @return MessageEntity[]|null
     */
    public function getEntities(): ?array
    {
        return $this->entities;
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