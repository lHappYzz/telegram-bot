<?php

namespace Boot\Src\Entities\InlineMode\InputMessageContent;

/**
 * @link https://core.telegram.org/bots/api#inputlocationmessagecontent
 */
class InputLocationMessageContent extends InputMessageContent
{
    /**
     * @param float $latitude
     * @param float $longitude
     * @param float|null $horizontalAccuracy
     * @param int|null $livePeriod
     * @param int|null $heading
     * @param int|null $proximityAlertRadius
     */
    public function __construct(
        protected float $latitude,
        protected float $longitude,
        protected ?float $horizontalAccuracy = null,
        protected ?int $livePeriod = null,
        protected ?int $heading = null,
        protected ?int $proximityAlertRadius = null,
    ) {}

    /**
     * @param float $horizontalAccuracy
     * @return InputLocationMessageContent
     */
    public function setHorizontalAccuracy(float $horizontalAccuracy): self
    {
        $this->horizontalAccuracy = $horizontalAccuracy;

        return $this;
    }

    /**
     * @param int $livePeriod
     * @return InputLocationMessageContent
     */
    public function setLivePeriod(int $livePeriod): self
    {
        $this->livePeriod = $livePeriod;

        return $this;
    }

    /**
     * @param int $heading
     * @return InputLocationMessageContent
     */
    public function setHeading(int $heading): self
    {
        $this->heading = $heading;

        return $this;
    }

    /**
     * @param int $proximityAlertRadius
     * @return InputLocationMessageContent
     */
    public function setProximityAlertRadius(int $proximityAlertRadius): self
    {
        $this->proximityAlertRadius = $proximityAlertRadius;

        return $this;
    }


}