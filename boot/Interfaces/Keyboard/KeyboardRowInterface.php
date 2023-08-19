<?php

namespace Boot\Interfaces\Keyboard;

use JsonSerializable;

interface KeyboardRowInterface extends JsonSerializable
{
    /**
     * Add new button to the row
     *
     * @param string $text
     * @return KeyboardButtonInterface
     */
    public function addButton(string $text): KeyboardButtonInterface;

    /**
     * Get all buttons of the row
     *
     * @return array
     */
    public function getButtons(): array;
}