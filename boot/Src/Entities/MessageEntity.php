<?php

namespace Boot\Src\Entities;

use Boot\Src\Abstracts\JsonSerializableEntity;

/**
 * @link https://core.telegram.org/bots/api#messageentity
 */
class MessageEntity extends JsonSerializableEntity
{
    public const string MENTION_TYPE = 'mention';
    public const string HASHTAG_TYPE = 'hashtag';
    public const string CASHTAG_TYPE = 'cashtag';
    public const string BOT_COMMAND_TYPE = 'bot_command';
    public const string URL_TYPE = 'url';
    public const string EMAIL_TYPE = 'email';
    public const string PHONE_NUMBER_TYPE = 'phone_number';
    public const string BOLD_TYPE = 'bold';
    public const string ITALIC_TYPE = 'italic';
    public const string UNDERLINE_TYPE = 'underline';
    public const string STRIKETHROUGH_TYPE = 'strikethrough';
    public const string SPOILER_TYPE = 'spoiler';
    public const string CODE_TYPE = 'code';
    public const string PRE_TYPE = 'pre';
    public const string TEXT_LINK_TYPE = 'text_link';
    public const string TEXT_MENTION_TYPE = 'text_mention';
    public const string CUSTOM_EMOJI_TYPE = 'custom_emoji';

    /**
     * @param string $type
     * @param int $offset
     * @param int $length
     * @param int|null $url
     * @param TelegramUser|null $user
     * @param string|null $language
     * @param string|null $customEmojiId
     */
    public function __construct(
        protected string $type,
        protected int $offset,
        protected int $length,
        protected ?int $url = null,
        protected ?TelegramUser $user = null,
        protected ?string $language = null,
        protected ?string $customEmojiId = null,
    ) {}

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @return int
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * @return int|null
     */
    public function getUrl(): ?int
    {
        return $this->url;
    }

    /**
     * @return TelegramUser|null
     */
    public function getUser(): ?TelegramUser
    {
        return $this->user;
    }

    /**
     * @return string|null
     */
    public function getLanguage(): ?string
    {
        return $this->language;
    }

    /**
     * @return string|null
     */
    public function getCustomEmojiId(): ?string
    {
        return $this->customEmojiId;
    }
}