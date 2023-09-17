<?php

namespace Boot\Src\Entities\InlineMode;

use Boot\Traits\WithInputMessageContent;

/**
 * @link https://core.telegram.org/bots/api#inlinequeryresultlocation
 */
class InlineQueryResultLocation extends InlineQueryResult
{
    use WithInputMessageContent;

    /**
     * @param string $id
     * @param float $latitude
     * @param float $longitude
     * @param string $title
     * @param float|null $horizontalAccuracy
     * The radius of uncertainty for the location, measured in meters; 0-1500
     * @param int|null $livePeriod
     * Period in seconds for which the location can be updated, should be between 60 and 86400.
     * @param int|null $heading
     * Period in seconds for which the location can be updated, should be between 60 and 86400.
     * @param int|null $proximityAlertRadius
     * For live locations, a maximum distance for proximity alerts about approaching another chat member, in meters.
     * Must be between 1 and 100000 if specified.
     * @param string|null $thumbnailUrl
     * @param int|null $thumbnailWidth
     * @param int|null $thumbnailHeight
     */
    public function __construct(
        string $id,
        protected float $latitude,
        protected float $longitude,
        protected string $title,
        protected ?float $horizontalAccuracy = null,
        protected ?int $livePeriod = null,
        protected ?int $heading = null,
        protected ?int $proximityAlertRadius = null,
        protected ?string $thumbnailUrl = null,
        protected ?int $thumbnailWidth = null,
        protected ?int $thumbnailHeight = null,
    ) {
        parent::__construct($id);
    }

    /**
     * @inheritDoc
     */
    protected function getType(): string
    {
        return 'location';
    }
}