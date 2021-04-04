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
        $this->request::sendTelegramRequest(['token' => $this->TOKEN, 'method' => 'setWebhook', 'url' => 'https://' . $this->BOT_URL]);
    }

    public function sendMessage($message = '') {
        $this->request::sendTelegramRequest(['token' => $this->TOKEN, 'method' => 'sendMessage', 'text' => $message, 'chat_id' => $this->getChatID()]);
    }

    public function handle() {
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

        $this->sendMessage(
            'id: ' . $this->user->getID()."\n".
            'username: ' . $this->user->getUserName()."\n".
            'first name: ' . $this->user->getFirstName()."\n".
            'last name: ' . $this->user->getLastName()."\n".
            'language code: ' . $this->user->getLanguageCode()
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