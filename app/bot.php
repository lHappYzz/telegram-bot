<?php

namespace App;

use App\Commands\baseCommand;
use App\Config\Config;
use Boot\Src\telegramChat;
use Boot\Interfaces\botInterface;
use Boot\Traits\helpers;
use Boot\Src\ReplyMarkup\InlineKeyboardMarkup;
use Boot\Traits\http;

class bot extends telegramChat implements botInterface
{
    use helpers, http;

    private $TOKEN;
    private $BOT_URL;

    public function __construct()
    {
        parent::__construct();

        $config = Config::bot();

        $this->TOKEN = $config['bot_token'];
        $this->BOT_URL = $config['bot_url'];
    }

    public function setWebhook()
    {
        return $this->request::sendTelegramRequest(['token' => $this->TOKEN, 'method' => 'setWebhook', 'url' => 'https://' . $this->BOT_URL]);
    }

    public function sendMessage($message = ''): void
    {
        $this->request::sendTelegramRequest([
            'parse_mode' => 'Markdown',
            'token' => $this->TOKEN,
            'method' => 'sendMessage',
            'text' => $message,
            'chat_id' => $this->getChatID(),

        ]);
    }

    //TODO: Refactor messages sending
    public function sendMessageV2(string $message, telegramChat $chat, InlineKeyboardMarkup $inlineKeyboardMarkup): void
    {
        $this->request::sendTelegramRequest([
            'token' => $this->TOKEN,
            'method' => 'sendMessage',
            'text' => $message,
            'chat_id' => $chat->getChatID(),
            'reply_markup' => $inlineKeyboardMarkup->getInlineKeyboard(),
        ]);
    }

    public function sendPhoto($fileID, $caption = ''): void
    {
        $this->request::sendTelegramRequest([
            'parse_mode' => 'Markdown',
            'token' => $this->TOKEN,
            'method' => 'sendPhoto',
            'photo' => $fileID,
            'caption' => $caption,
            'chat_id' => $this->getChatID(),
        ]);
    }

    public function handle(): void
    {
        if ($this->message->isCommand()) {
            $this->handleCommand();
        }

        $repliedMessage = $this->message->getRepliedMessage();
        if ($repliedMessage) {
            $this->sendMessage('Detected the reply for message: ' .
                $repliedMessage->getMessageText() .
                ' that was sent at: ' . $repliedMessage->getMessageDate()
            );
        }
    }

    private function handleCommand(): void
    {
        $command = $this->getCommandClassInstance($this->message->getCommandClassName());
        if ($command instanceof baseCommand){
            $command->boot($this);
        } else {
            $this->sendMessage('I can not recognize the command - <' . $this->message->getMessageText() . '>');
        }
    }
}