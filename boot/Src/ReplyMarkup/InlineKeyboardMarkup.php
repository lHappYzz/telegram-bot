<?php

namespace Boot\Src\ReplyMarkup;

use JsonException;

/**
 * Class InlineKeyboardMarkup
 * @link https://core.telegram.org/bots/api/#inlinekeyboardmarkup
 */
final class InlineKeyboardMarkup extends ReplyMarkup
{
    private array $inlineKeyboard = [
        'inline_keyboard' => [],
    ];

    /**
     * Create new row for buttons
     * @return InlineKeyboardRow
     */
    public function addKeyboardRow(): InlineKeyboardRow
    {
        $inlineKeyboardRow = new InlineKeyboardRow();

        $this->inlineKeyboard['inline_keyboard'][] = &$inlineKeyboardRow;

        return $inlineKeyboardRow;
    }

    /**
     * Get all keyboard rows
     * @return InlineKeyboardRow[]
     */
    public function getKeyboardRows(): array
    {
        return $this->inlineKeyboard['inline_keyboard'];
    }

    /**
     * Collect all the necessary, according to the telegram API documentation, class fields into an array
     * @return array
     */
    protected function toArray(): array
    {
        $inlineKeyboard = $this->inlineKeyboard;

        /** @var InlineKeyboardRow $inlineKeyboardRow */
        foreach ($inlineKeyboard['inline_keyboard'] as &$inlineKeyboardRow) {
            $inlineKeyboardRow = $inlineKeyboardRow->toArray();
        }

        return $inlineKeyboard;
    }

    /**
     * Result will be used as parameter for reply_markup field
     * @return string
     * @throws JsonException
     */
    public function __toString(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }
}