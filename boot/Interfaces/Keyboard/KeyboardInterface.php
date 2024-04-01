<?php

namespace Boot\Interfaces\Keyboard;

use JsonSerializable;

interface KeyboardInterface extends JsonSerializable
{
    /**
     * Create new row for buttons
     *
     * @return KeyboardRowInterface
     */
    public function addKeyboardRow(): KeyboardRowInterface;

    /**
     * Get all keyboard rows
     *
     * @return KeyboardRowInterface[]
     */
    public function getKeyboardRows(): array;
}