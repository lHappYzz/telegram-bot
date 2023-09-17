<?php

namespace Boot\Src\Entities\InlineMode;

/**
 * @link https://core.telegram.org/bots/api#inlinequeryresultvoice
 */
class InlineQueryResultVoice extends InlineQueryFileResult
{
    /**
     * @param string $id
     * @param string $voiceUrl
     * @param string $title
     * @param string|null $caption
     * @param int|null $voiceDuration
     * Recording duration in seconds
     */
    public function __construct(
        string $id,
        protected string $voiceUrl,
        protected string $title,
        protected ?string $caption = null,
        protected ?int $voiceDuration = null,
    ) {
        parent::__construct($id);
    }

    /**
     * @inheritDoc
     */
    protected function getType(): string
    {
        return 'voice';
    }
}