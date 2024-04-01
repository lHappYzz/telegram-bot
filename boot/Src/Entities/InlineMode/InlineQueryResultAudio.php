<?php

namespace Boot\Src\Entities\InlineMode;

/**
 * @link https://core.telegram.org/bots/api#inlinequeryresultaudio
 */
class InlineQueryResultAudio extends InlineQueryFileResult
{
    /**
     * @param string $id
     * @param string $audioUrl
     * @param string $title
     * @param string|null $caption
     * @param string|null $performer
     * @param int|null $audioDuration
     */
    public function __construct(
        protected string $id,
        protected string $audioUrl,
        protected string $title,
        protected ?string $caption = null,
        protected ?string $performer = null,
        protected ?int $audioDuration = null,

    ) {
        parent::__construct($id);
    }

    /**
     * @inheritDoc
     */
    protected function getType(): string
    {
        return 'audio';
    }
}