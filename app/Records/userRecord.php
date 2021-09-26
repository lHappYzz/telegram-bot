<?php


namespace App\Records;

use Boot\Database\record;

class userRecord extends record {

    protected static $tableName = 'user';

    private $id;
    private $firstName;
    private $lastName;
    private $middleName;
    private $languageCode;

    public $isBot;

    public function getID() {
        return $this->id;
    }
    public function getFirstName() {
        return $this->firstName;
    }
    public function getLastName() {
        return $this->lastName;
    }
    public function getMiddleName() {
        return $this->middleName;
    }
    public function getLanguageCode() {
        return $this->languageCode;
    }
}