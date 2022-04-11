<?php

namespace App;

use App\Commands\baseCommand;
use App\Config\Config;
use BadMethodCallException;
use Boot\Log\Logger;
use Boot\Src\CallbackQueryHandler;
use Boot\Src\Entity;
use Boot\Src\ReplyMarkup\ReplyMarkup;
use Boot\Src\telegram;
use Boot\Src\telegramChat;
use Boot\Src\Update;
use Boot\Traits\helpers;
use Boot\Traits\http;
use Throwable;

class bot extends Entity
{
    use http;
    use helpers;

    private $TOKEN;

    protected telegram $telegram;
    public static Update $update;

    public function __construct(telegram $telegram)
    {
        $this->telegram = $telegram;
        self::$update = &$telegram->request->update;

        $config = Config::bot();

        $this->TOKEN = $config['bot_token'];
    }

    public function sendMessage(
        string $text,
        telegramChat $chat,
        ?ReplyMarkup $replyMarkup = null,
        ?string $parseMode = null,
        bool $disableWebPagePreview = false,
        bool $disableNotification = false,
        bool $protectContent = false,
        ?string $replyToMessageId = null,
        bool $allowSendingWithoutReply = false
    ): void {
        try {
            $this->telegram->request::sendTelegramRequest([
                'token' => $this->TOKEN,
                'method' => 'sendMessage',
                'text' => $text,
                'chat_id' => $chat->getChatID(),
                'parse_mode' => $parseMode,
                'disable_web_page_preview' => $disableWebPagePreview,
                'disable_notification' => $disableNotification,
                'protect_content' => $protectContent,
                'reply_to_message_id' => $replyToMessageId,
                'allow_sending_without_reply' => $allowSendingWithoutReply,
                'reply_markup' => $replyMarkup ? json_encode($replyMarkup, JSON_THROW_ON_ERROR) : null,
            ]);
        } catch (Throwable $e) {
            Logger::logException($e, Logger::LEVEL_ERROR);
        }
    }

    public function editMessageText(
        string $text,
        telegramChat $chat,
        int $messageId,
        ?ReplyMarkup $replyMarkup = null,
        ?string $parseMode = null,
        bool $disableWebPagePreview = false
    ): void {
        try {
            $this->telegram->request::sendTelegramRequest([
                'token' => $this->TOKEN,
                'method' => 'editMessageText',
                'text' => $text,
                'chat_id' => $chat->getChatID(),
                'message_id' => $messageId,
                'parse_mode' => $parseMode,
                'disable_web_page_preview' => $disableWebPagePreview,
                'reply_markup' => $replyMarkup ? json_encode($replyMarkup, JSON_THROW_ON_ERROR) : null,
            ]);
        } catch (Throwable $e) {
            Logger::logException($e, Logger::LEVEL_ERROR);
        }
    }

    public function sendPhoto($fileID, $caption = ''): void
    {
        $this->telegram->request::sendTelegramRequest([
            'parse_mode' => 'Markdown',
            'token' => $this->TOKEN,
            'method' => 'sendPhoto',
            'photo' => $fileID,
            'caption' => $caption,
            'chat_id' => $this->getChat()->getChatID(),
        ]);
    }

    public function handle(): void
    {
        try {
            $this->handleCallbackQuery();
        } catch (BadMethodCallException $exception) {
            if ($this->getMessage()->isCommand()) {
                $this->handleCommand();
            }
        }

        $repliedMessage = $this->getMessage()->getRepliedMessage();
        if ($repliedMessage) {
            $this->sendMessage(
                'Detected the reply for message: ' .
                $repliedMessage->getMessageText() .
                ' that was sent at: ' . $repliedMessage->getMessageDate(),
                $this->getChat()
            );
        }
    }

    private function handleCallbackQuery(): void
    {
        $callbackQuery = $this->getCallbackQuery();
        if (
            ($handler = $this->getCallbackQueryHandlerClassInstance(
                $this->resolveCallbackQueryHandlerName($callbackQuery->getData())
            )) instanceof CallbackQueryHandler
        ) {
            $handler->handle($this, $callbackQuery);
        }
    }

    private function handleCommand(): void
    {
        $command = $this->getCommandClassInstance($this->getMessage()->getCommandClassName());
        if ($command instanceof baseCommand){
            $command->boot($this);
        } else {
            $this->sendMessage(
                'I can not recognize the command - <' .
                $this->getMessage()->getMessageText() . '>',
                $this->getChat()
            );
        }
    }
}