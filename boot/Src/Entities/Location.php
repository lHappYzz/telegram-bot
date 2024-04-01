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
        protected float $longitude,
        protected float $latitude,
        protected ?float $horizontalAccuracy = null,
        protected ?int$livePeriod = null,
        protected ?int $heading = null,
        protected ?int $proximityAlertRadius = null,
    ) {}
}