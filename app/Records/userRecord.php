<?php


namespace App\Records;

use Boot\Database\record;

class userRecord extends record {

    protected string $table = 'user';
    protected array $fillable = ['firstName', 'lastName', 'userName', 'languageCode', 'isBot'];

    protected int $id;
    protected string $firstName;
    protected string $lastName;
    protected string $middleName;
    protected string $languageCode;

    protected bool $isBot;

    public function getID(): int {
        return $this->id;
    }
    public function getFirstName(): string {
        return $this->firstName;
    }
    public function getLastName(): string {
        return $this->lastName;
    }
    public function getMiddleName(): string {
        return $this->middleName;
    }
    public function getLanguageCode(): string {
        return $this->languageCode;
    }
    public function isBot(): bool {
        return $this->isBot;
    }
    public function setID($id): void {
        $this->id = $id;
    }
    public function setFirstName($firstName): void {
        $this->firstName = $firstName;
    }
    public function setLastName($lastName): void {
        $this->lastName = $lastName;
    }
    public function setMiddleName($middleName): void {
        $this->middleName = $middleName;
    }
    public function setLanguageCode($languageCode): void {
        $this->languageCode = $languageCode;
    }
    public function setIsBot($isBot): void {
        $this->isBot = $isBot;
    }
}