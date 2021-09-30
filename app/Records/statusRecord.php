<?php


namespace App\Records;

use Boot\Database\record;

class statusRecord extends record {

    protected static string $tableName = 'status';
    protected static array $fillable = ['name', 'description'];

    protected int $id;
    protected string $name;
    protected string $description;

    public function getID(): int {
        return $this->id;
    }
    public function getName(): string {
        return $this->name;
    }
    public function getDescription(): string {
        return $this->description;
    }
    public function setID($id): void {
        $this->id = $id;
    }
    public function setName($name): void {
        $this->name = $name;
    }
    public function setDescription($description): void {
        $this->description = $description;
    }
}