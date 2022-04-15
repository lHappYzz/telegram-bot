<?php

namespace Boot\Src\ReplyMarkup;

class InlineKeyboardRow extends ReplyMarkup
{
    private array $buttons = [];

    /**
     * Add new button to the InlineKeyboardRow
     * @see InlineKeyboardRow
     * @param string $text
     * @param string $callbackData
     * @param string|null $url
     */
    public function addButton(string $text, string $callbackData, ?string $url = null): void
    {
        $this->buttons[] = new InlineKeyboardButton($text, $callbackData, $url);
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
        return array_map(static function (InlineKeyboardButton $inlineKeyboardButton) {
            return $inlineKeyboardButton->jsonSerialize();
        }, $this->buttons);
    }
}