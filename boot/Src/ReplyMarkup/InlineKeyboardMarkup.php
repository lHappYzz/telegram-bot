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
     * Specify data which should be serialized to JSON
     * @return array data which can be serialized by <b>json_encode</b>,
     */
    public function jsonSerialize(): array
    {
        $inlineKeyboard = $this->inlineKeyboard;

        /** @var InlineKeyboardRow $inlineKeyboardRow */
        foreach ($inlineKeyboard['inline_keyboard'] as &$inlineKeyboardRow) {
            $inlineKeyboardRow = $inlineKeyboardRow->jsonSerialize();
        }

        return $inlineKeyboard;
    }
}