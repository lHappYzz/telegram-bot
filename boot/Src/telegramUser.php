<?php


namespace Boot\Src;


class telegramUser {

    private $id;
    private $firstName;
    private $lastName;
    private $userName;
    private $languageCode;
    private $isBot;

    public function __construct($userData) {
        $this->id = $userData['id'];
        $this->firstName = $userData['first_name'];
        $this->lastName = $userData['last_name'];
        $this->userName = $userData['username'];
        $this->languageCode = $userData['language_code'];
        $this->isBot = $userData['isBot'];
    }

    public function getID() {
        return $this->id;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getUserName() {
        return $this->userName;
    }

    public function getLanguageCode() {
        return $this->languageCode;
    }

    public function isBot() {
        return $this->isBot;
    }
}