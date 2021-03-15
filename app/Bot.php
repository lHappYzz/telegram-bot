<?php

namespace App;

use App\Commands\baseCommand;
use Boot\Src\TelegramChat;
use Boot\Interfaces\BotInterface;
use Boot\Traits\Helpers;

class Bot extends TelegramChat implements BotInterface
{
    use Helpers;

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
        if ($this->message->isCommand()) {
            $this->handleCommand();
        }

        $this->sendMessage(
            '<' . $this->message->getMessageText() . '> ' . $this->message->getEventDescription() . ': ' .
            $this->message->getMessageDate()
        );
    }

    private function handleCommand() {
        $command = $this->getCommandClassInstance($this->message->getCommandClassName());
        if ($command instanceof baseCommand){
            $command->boot($this);
        } else {
            $this->sendMessage('I can not recognize the command - <' . $this->message->getMessageText() . '>');
        }
    }
}