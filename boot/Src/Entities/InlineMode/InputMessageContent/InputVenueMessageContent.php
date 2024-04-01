<?php

namespace Boot\Src\Entities\InlineMode\InputMessageContent;

/**
 * @link https://core.telegram.org/bots/api#inputvenuemessagecontent
 */
class InputVenueMessageContent extends InputMessageContent
{
    /**
     * @param float $latitude
     * @param float $longitude
     * @param string $title
     * @param string $address
     * @param string|null $foursquareId
     * @param string|null $foursquareType
     * @param string|null $googlePlaceId
     * @param string|null $googlePlaceType
     */
    public function __construct(
        protected float $latitude,
        protected float $longitude,
        protected string $title,
        protected string $address,
        protected ?string $foursquareId = null,
        protected ?string $foursquareType = null,
        protected ?string $googlePlaceId = null,
        protected ?string $googlePlaceType = null,
    ) {}

    /**
     * @param string $foursquareId
     * @return InputVenueMessageContent
     */
    public function setFoursquareId(string $foursquareId): self
    {
        $this->foursquareId = $foursquareId;

        return $this;
    }

    /**
     * @param string $foursquareType
     * @return InputVenueMessageContent
     */
    public function setFoursquareType(string $foursquareType): self
    {
        $this->foursquareType = $foursquareType;

        return $this;
    }

    /**
     * @param string $googlePlaceId
     * @return InputVenueMessageContent
     */
    public function setGooglePlaceId(string $googlePlaceId): self
    {
        $this->googlePlaceId = $googlePlaceId;

        return $this;
    }

    /**
     * @param string $googlePlaceType
     * @return InputVenueMessageContent
     */
    public function setGooglePlaceType(string $googlePlaceType): self
    {
        $this->googlePlaceType = $googlePlaceType;

        return $this;
    }


}