<?php

namespace App;

use Boot\Src\TelegramChat;
use Boot\Interfaces\BotInterface;

class Bot extends TelegramChat implements BotInterface
{
    private $TOKEN;
    private $BOT_URL;

    public function __construct($config) {
        parent::__construct();

        $this->TOKEN = $config['bot_token'];
        $this->BOT_URL = $config['bot_url'];
    }

    public function setWebhook() {
        $this->request->sendRequest(['token' => $this->TOKEN, 'method' => 'setWebhook', 'url' => 'https://' . $this->BOT_URL]);
    }

    public function sendMessage($message = '') {
        $this->request->sendRequest(['token' => $this->TOKEN, 'method' => 'sendMessage', 'text' => $message, 'chat_id' => $this->getChatID()]);
    }

    public function handle() {
        $this->sendMessage(
            '<' . $this->message->getMessageText() . '> ' . $this->message->getEventDescription() . ': ' .
            $this->message->getMessageDate()
        );
    }
}