<?php

namespace Boot\Src;

class telegramChat extends telegram {
    private $id;
    private $type;
    private $firstName;
    private $lastName;
    private $userName;

    public telegramMessage $message;
    public telegramUser $user;

    public function __construct() {
        parent::__construct();

        $update = $this->request->getUpdate();
        $updateType = $this->request->getUpdateType();

        $this->message = new telegramMessage($update[$updateType]);
        $this->user = new telegramUser($update[$updateType]['from']);

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