<?php

namespace Boot\Src;

class TelegramChat extends Telegram {
    private $id;
    private $type;
    private $firstName;
    private $lastName;
    private $userName;

    public TelegramMessage $message;
    public TelegramUser $user;

    public function __construct() {
        parent::__construct();

        $update = $this->request->getUpdate();
        $updateType = $this->request->getUpdateType();

        $this->message = new TelegramMessage($update[$updateType]);
        $this->user = new TelegramUser($update[$updateType]['from']);

        $this->id = $update[$updateType]['chat']['id'];
        $this->type = $update[$updateType]['chat']['type'];
        $this->firstName = $update[$updateType]['chat']['firstName'];
        $this->lastName = $update[$updateType]['chat']['lastName'];
        $this->userName = $update[$updateType]['chat']['userName'];
    }

    public function getChatID() {
        if (empty($this->id)) return '423303268';
        return $this->id;
    }

    protected function getChatType() {
        return $this->type;
    }
}