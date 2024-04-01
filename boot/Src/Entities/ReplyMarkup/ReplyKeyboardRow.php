<?php

namespace Boot\Src\Entities\ReplyMarkup;

use Boot\Interfaces\Keyboard\KeyboardRowInterface;

class ReplyKeyboardRow implements KeyboardRowInterface
{
    /** @var ReplyKeyboardButton[] */
    protected array $buttons = [];

    /**
     * @param string $text
     * @return ReplyKeyboardButton
     */
    public function addButton(string $text): ReplyKeyboardButton
    {
        return $this->buttons[] = new ReplyKeyboardButton($text);
    }

    /**
     * @return ReplyKeyboardButton[]
     */
    public function getButtons(): array
    {
        return $this->buttons;
    }

    /**
     * Specify data which should be serialized to JSON
     * @return array data which can be serialized by <b>json_encode</b>,
     */
    public function jsonSerialize(): array
    {
        return $this->buttons;
    }
}