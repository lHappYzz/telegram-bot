<?php

namespace Boot\Src\Entities\ReplyMarkup;

use Boot\Interfaces\Keyboard\KeyboardRowInterface;

class InlineKeyboardRow implements KeyboardRowInterface
{
    /** @var array */
    protected array $buttons = [];

    /**
     * @param string $text
     * @return InlineKeyboardButton
     */
    public function addButton(string $text): InlineKeyboardButton
    {
        return $this->buttons[] = new InlineKeyboardButton($text);
    }

    /**
     * @return array
     */
    public function getButtons(): array
    {
        return $this->buttons;
    }

    /**
     * @return InlineKeyboardButton[]
     */
    public function getKeyboardButtons(): array
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