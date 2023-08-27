<?php

namespace Boot\Src\Entities;

use Boot\Src\Abstracts\JsonSerializableEntity;

/**
 * @link https://core.telegram.org/bots/api#messageentity
 */
class MessageEntity extends JsonSerializableEntity
{
    public const MENTION_TYPE = 'mention';
    public const HASHTAG_TYPE = 'hashtag';
    public const CASHTAG_TYPE = 'cashtag';
    public const BOT_COMMAND_TYPE = 'bot_command';
    public const URL_TYPE = 'url';
    public const EMAIL_TYPE = 'email';
    public const PHONE_NUMBER_TYPE = 'phone_number';
    public const BOLD_TYPE = 'bold';
    public const ITALIC_TYPE = 'italic';
    public const UNDERLINE_TYPE = 'underline';
    public const STRIKETHROUGH_TYPE = 'strikethrough';
    public const SPOILER_TYPE = 'spoiler';
    public const CODE_TYPE = 'code';
    public const PRE_TYPE = 'pre';
    public const TEXT_LINK_TYPE = 'text_link';
    public const TEXT_MENTION_TYPE = 'text_mention';
    public const CUSTOM_EMOJI_TYPE = 'custom_emoji';

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
}