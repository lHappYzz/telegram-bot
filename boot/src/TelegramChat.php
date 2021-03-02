<?php

namespace Boot\Src;

class TelegramChat extends Telegram {
    private $id;
    private $type;
    private $firstName;
    private $lastName;
    private $userName;

    public TelegramMessage $message;

    public function __construct() {
        parent::__construct();
        $this->message = new TelegramMessage($this->request->getUpdate());
        $chat = $this->message->getChat();

        $this->id = $chat['id'];
        $this->type = $chat['type'];
        $this->firstName = $chat['firstName'];
        $this->lastName = $chat['lastName'];
        $this->userName = $chat['userName'];
    }

    public function getChatID() {
        if (empty($this->id)) return '423303268';
        return $this->id;
    }
    protected function getChatType() {
        return $this->type;
    }
}