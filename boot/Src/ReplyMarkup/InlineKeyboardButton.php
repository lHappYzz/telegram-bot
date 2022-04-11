<?php

namespace Boot\Src\ReplyMarkup;

/**
 * Class InlineKeyboardButton
 * @link https://core.telegram.org/bots/api#inlinekeyboardbutton
 */
class InlineKeyboardButton extends ReplyMarkup
{
    protected string $text;
    protected string $callbackData;
    protected ?string $url;

    public function __construct(string $text, string $callbackData, ?string $url = null)
    {
        $this->text = $text;
        $this->url = $url;
        $this->callbackData = $callbackData;
    }


    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function setCallbackData(string $callbackData): void
    {
        $this->callbackData = $callbackData;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getCallbackData(): string
    {
        return $this->callbackData;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * Collect all the necessary, according to the telegram API documentation, class fields into an array
     * @return array
     */
    protected function toArray(): array
    {
        $resultArray = [];

        foreach (get_object_vars($this) as $name => $value) {
            if (!empty($value)) {
                $resultArray[$this->camelCaseToSnakeCase($name)] = $value;
            }
        }

        return $resultArray;
    }

    /**
     * Convert our camel case style class fields to snake case as expected by the telegram API
     * @param string $input
     * @return string
     */
    private function camelCaseToSnakeCase(string $input): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }
}