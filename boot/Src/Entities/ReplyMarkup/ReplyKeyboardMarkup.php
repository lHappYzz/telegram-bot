<?php

namespace Boot\Src\Entities\ReplyMarkup;

use Boot\Interfaces\Keyboard\KeyboardInterface;

/**
 * This object represents a custom keyboard with reply options
 * @link https://core.telegram.org/bots/api#replykeyboardmarkup
 */
class ReplyKeyboardMarkup extends ReplyMarkup implements KeyboardInterface
{
    /** @var ReplyKeyboardRow[] */
    protected array $keyboard = [];

    /**
     * @param bool|null $isPersistent
     * @param bool|null $resizeKeyboard
     * @param bool|null $oneTimeKeyboard
     * @param string|null $inputFieldPlaceholder
     * @param bool|null $selective
     */
    public function __construct(
        protected ?bool $isPersistent = null,
        protected ?bool $resizeKeyboard = null,
        protected ?bool $oneTimeKeyboard = null,
        protected ?string $inputFieldPlaceholder = null,
        protected ?bool $selective = null,
    ) {}

    /**
     * Create new row for buttons
     *
     * @return ReplyKeyboardRow
     */
    public function addKeyboardRow(): ReplyKeyboardRow
    {
        return $this->keyboard[] = new ReplyKeyboardRow();
    }

    /**
     * Get all keyboard rows
     *
     * @return ReplyKeyboardRow[]
     */
    public function getKeyboardRows(): array
    {
        return $this->keyboard;
    }
}