<?php

namespace Boot\Src\ReplyMarkup;

use Boot\Log\Logger;

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
     * Function result will be used as parameter for reply_markup field
     * @return string
     */
    public function getInlineKeyboard(): string
    {
        try {
            return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            Logger::logException($e, Logger::LEVEL_ERROR);
        }

        return '';
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
}