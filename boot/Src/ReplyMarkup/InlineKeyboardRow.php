<?php

namespace Boot\Src\ReplyMarkup;

class InlineKeyboardRow extends ReplyMarkup
{
    private array $buttons = [];

    public function addButton(string $text, string $callbackData, ?string $url = null): void
    {
        $this->buttons[] = new InlineKeyboardButton($text, $callbackData, $url);
    }

    /**
     * Collect all the necessary, according to the telegram API documentation, class fields into an array
     * @return array
     */
    protected function toArray(): array
    {
        return array_map(static function (InlineKeyboardButton $inlineKeyboardButton) {
            return $inlineKeyboardButton->toArray();
        }, $this->buttons);
    }
}