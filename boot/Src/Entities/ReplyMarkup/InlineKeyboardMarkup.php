<?php

namespace Boot\Src\Entities\ReplyMarkup;

use Boot\Interfaces\Keyboard\KeyboardInterface;

/**
 * Class InlineKeyboardMarkup
 * @link https://core.telegram.org/bots/api/#inlinekeyboardmarkup
 */
final class InlineKeyboardMarkup extends ReplyMarkup implements KeyboardInterface
{
    /** @var InlineKeyboardRow[] */
    protected array $inlineKeyboard = [];

    /**
     * @return InlineKeyboardRow
     */
    public function addKeyboardRow(): InlineKeyboardRow
    {
        return $this->inlineKeyboard[] = new InlineKeyboardRow();
    }

    /**
     * @return InlineKeyboardRow[]
     */
    public function getKeyboardRows(): array
    {
        return $this->inlineKeyboard;
    }

}