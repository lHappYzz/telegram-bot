<?php

namespace Boot\Src\Entities\ReplyMarkup;

use Boot\Interfaces\Keyboard\KeyboardButtonInterface;
use Boot\Src\Abstracts\CallbackQueryHandler;
use Boot\Src\Abstracts\JsonSerializableEntity;
use Boot\Traits\Helpers;
use RuntimeException;

/**
 * Class InlineKeyboardButton
 * @link https://core.telegram.org/bots/api#inlinekeyboardbutton
 */
class InlineKeyboardButton extends JsonSerializableEntity implements KeyboardButtonInterface
{
    use Helpers;

    /** @var string */
    public const CALLBACK_DATA_DELIMITER = ':';

    /**
     * @param string $text
     * @param string|null $callbackData
     * @param string|null $url
     * @param string|null $switchInlineQuery
     * @param string|null $switchInlineQueryCurrentChat
     * @param SwitchInlineQueryChosenChat|null $switchInlineQueryChosenChat
     */
    public function __construct(
        protected string $text,
        protected ?string $callbackData = null,
        protected ?string $url = null,
        protected ?string $switchInlineQuery = null,
        protected ?string $switchInlineQueryCurrentChat = null,
        protected ?SwitchInlineQueryChosenChat $switchInlineQueryChosenChat = null,
    ) {}

    /**
     * Specify which class should be used as handler for the given callback data
     *
     * @param string $abstract
     * A fully-qualified handler class name
     *
     * @param string $callbackData
     * Data to be sent in a callback query to the bot when button is pressed, 1-64 bytes
     *
     * @return void
     */
    public function addCallbackHandler(string $abstract, string $callbackData): void
    {
        if (!is_a($abstract, CallbackQueryHandler::class, true)) {
            throw new RuntimeException('Bad handler name given.');
        }

        $callbackData = $this->compress($abstract, $callbackData);

        if (strlen($callbackData) > 64) {
            throw new RuntimeException(
                'Cant use this button settings because after compression it has more than 64 bytes. Compressed string: ' .
                $callbackData . '(' . strlen($callbackData) . '  bytes).'
            );
        }

        $this->callbackData = $callbackData;
    }

    /**
     * HTTP or tg:// URL to be opened when the button is pressed.
     * Links tg://user?id=<user_id> can be used to mention a user by their ID without using a username, if this is allowed by their privacy settings.
     *
     * @param string $url
     * @return void
     */
    public function addUrl(string $url): void
    {
        $this->url = $url;
    }

    public function addSwitchInlineQuery(string $switchInlineQuery): void
    {
        $this->switchInlineQuery = $switchInlineQuery;
    }

    public function addSwitchInlineQueryCurrentChat(string $switchInlineQuery): void
    {
        $this->switchInlineQueryCurrentChat = $switchInlineQuery;
    }

    public function addSwitchInlineQueryChosenChat(
        ?string $query = null,
        ?bool $allowUserChats = null,
        ?bool $allowBotChats = null,
        ?bool $allowGroupChats = null,
        ?bool $allowChannelChats = null,
    ): void {
        if (!$allowUserChats && !$allowBotChats && !$allowGroupChats && !$allowChannelChats) {
            throw new RuntimeException('At least one argument must be true.');
        }

        $this->switchInlineQueryChosenChat = new SwitchInlineQueryChosenChat(...func_get_args());
    }

    /**
     * @param string $handlerFullName
     * @param string $callbackData
     * @return string
     */
    private function compress(string $handlerFullName, string $callbackData): string
    {
        $handlerClassName = str_replace(
            CallbackQueryHandler::CALLBACK_QUERY_HANDLERS_ENDING,
            '',
            $this->arrayLast(
                explode(
                    '\\',
                    $handlerFullName
                )
            )
        );

        return $handlerClassName . InlineKeyboardButton::CALLBACK_DATA_DELIMITER . $callbackData;
    }
}