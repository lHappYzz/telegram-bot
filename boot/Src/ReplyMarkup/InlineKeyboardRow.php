<?php

namespace Boot\Src\ReplyMarkup;

use Boot\Log\Logger;
use Boot\Src\CallbackQueryHandler;
use Boot\Traits\Helpers;
use Exception;
use InvalidArgumentException;
use RuntimeException;

class InlineKeyboardRow extends ReplyMarkup
{
    use Helpers;

    private array $buttons = [];

    /**
     * Add new button to the InlineKeyboardRow
     * @param string $text
     * @param array $buttonSettings
     * @param string|null $url
     * @see InlineKeyboardRow
     */
    public function addButton(string $text, array $buttonSettings, ?string $url = null): void
    {
        try {
            $callbackData = $this->validateButtonSettingsAndFormCallbackData($buttonSettings);
        } catch (Exception $e) {
            Logger::logException($e, Logger::LEVEL_ERROR);
            die;
        }

        $this->buttons[] = new InlineKeyboardButton($text, $callbackData, $url);
    }

    /**
     * @param array $buttonSettings
     * @return string
     * @throws Exception
     */
    private function validateButtonSettingsAndFormCallbackData(array $buttonSettings): string
    {
        if (count($buttonSettings) > 2 || count($buttonSettings) < 1) {
            throw new InvalidArgumentException('Button settings can not have more than 2 elements.');
        }

        if (!is_a($this->arrayFirst($buttonSettings), CallbackQueryHandler::class, true)) {
            throw new InvalidArgumentException('Handler required');
        }

        if (!is_string($this->arrayLast($buttonSettings))) {
            throw new InvalidArgumentException('The second parameter of button settings must be a string.');
        }

        $callbackData = $this->createCallbackData($buttonSettings);

        if (strlen($callbackData) > 64) {
            throw new RuntimeException(
                'Cant use this button settings because after compression it has more than 64 bytes. Compressed string: ' .
                $callbackData . '(' . strlen($callbackData) . '  bytes).'
            );
        }

        return $callbackData;
    }

    public function createCallbackData(array $buttonSettings): string
    {
        $handlerClassName = str_replace(
            CallbackQueryHandler::CALLBACK_QUERY_HANDLERS_ENDING,
            '',
            $this->arrayLast(
                explode(
                    '\\',
                    $this->arrayFirst($buttonSettings)
                )
            )
        );
        return $handlerClassName . InlineKeyboardButton::CALLBACK_DATA_DELIMITER . $this->arrayLast($buttonSettings);
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