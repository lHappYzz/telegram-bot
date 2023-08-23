<?php

namespace Boot\Src\Entities;

use Boot\Src\Abstracts\Entity;

/**
 * This object represents a point on the map
 *
 * @link https://core.telegram.org/bots/api#location
 */
class Location extends Entity
{
    public function __construct(
        public float $longitude,
        public float $latitude,
        public ?float $horizontalAccuracy = null,
        public ?int$livePeriod = null,
        public ?int $heading = null,
        public ?int $proximityAlertRadius = null,
    ) {}
}