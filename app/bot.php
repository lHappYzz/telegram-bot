<?php

namespace App;

use App\Commands\baseCommand;
use App\Config\Config;
use Boot\Src\Entity;
use Boot\Src\telegram;
use Boot\Src\telegramChat;
use Boot\Src\Update;
use Boot\Traits\helpers;
use Boot\Src\ReplyMarkup\InlineKeyboardMarkup;
use Boot\Traits\http;

class bot extends Entity
{
    use http, helpers { getCommandClassInstance as protected; getCommandsInTheCommandDir as protected; }

    private $TOKEN;
    private $BOT_URL;

    protected telegram $telegram;
    public static Update $update;

    public function __construct(telegram $telegram)
    {
        $this->telegram = $telegram;
        self::$update = &$telegram->request->update;

        $config = Config::bot();

        $this->TOKEN = $config['bot_token'];
        $this->BOT_URL = $config['bot_url'];
    }

    public function setWebhook()
    {
        return $this->telegram->request::sendTelegramRequest(['token' => $this->TOKEN, 'method' => 'setWebhook', 'url' => 'https://' . $this->BOT_URL]);
    }

    public function sendMessage($message = ''): void
    {
        $this->telegram->request::sendTelegramRequest([
            'parse_mode' => 'Markdown',
            'token' => $this->TOKEN,
            'method' => 'sendMessage',
            'text' => $message,
            'chat_id' => $this->getChat()->getChatID(),
        ]);
    }

    //TODO: Refactor messages sending
    public function sendMessageV2(string $message, telegramChat $chat, InlineKeyboardMarkup $inlineKeyboardMarkup): void
    {
        $this->telegram->request::sendTelegramRequest([
            'token' => $this->TOKEN,
            'method' => 'sendMessage',
            'text' => $message,
            'chat_id' => $chat->getChatID(),
            'reply_markup' => $inlineKeyboardMarkup->getInlineKeyboard(),
        ]);
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
        if ($this->getMessage()->isCommand()) {
            $this->handleCommand();
        }

        $repliedMessage = $this->getMessage()->getRepliedMessage();
        if ($repliedMessage) {
            $this->sendMessage('Detected the reply for message: ' .
                $repliedMessage->getMessageText() .
                ' that was sent at: ' . $repliedMessage->getMessageDate()
            );
        }
    }

    private function handleCommand(): void
    {
        $command = $this->getCommandClassInstance($this->getMessage()->getCommandClassName());
        if ($command instanceof baseCommand){
            $command->boot($this);
        } else {
            $this->sendMessage('I can not recognize the command - <' . $this->getMessage()->getMessageText() . '>');
        }
    }
}