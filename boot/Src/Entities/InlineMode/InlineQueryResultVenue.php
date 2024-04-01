<?php

namespace Boot\Src\Entities\InlineMode;

use Boot\Traits\WithInputMessageContent;

/**
 * @link https://core.telegram.org/bots/api#inlinequeryresultvenue
 */
class InlineQueryResultVenue extends InlineQueryResult
{
    use WithInputMessageContent;

    /**
     * @param string $id
     * @param float $latitude
     * @param float $longitude
     * @param string $title
     * @param string $address
     * @param string|null $foursquareId
     * @param string|null $foursquareType
     * Foursquare type of the venue, if known. (For example, “arts_entertainment/default”,
     * “arts_entertainment/aquarium” or “food/icecream”.)
     * @param string|null $googlePlaceId
     * @param string|null $googlePlaceType
     * Google Places type of the venue.
     * @param string|null $thumbnailUrl
     * @param int|null $thumbnailWidth
     * @param int|null $thumbnailHeight
     * @link https://developers.google.com/places/web-service/supported_types
     */
    public function __construct(
        string $id,
        protected float $latitude,
        protected float $longitude,
        protected string $title,
        protected string $address,
        protected ?string $foursquareId = null,
        protected ?string $foursquareType = null,
        protected ?string $googlePlaceId = null,
        protected ?string $googlePlaceType = null,
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
        return 'venue';
    }
}