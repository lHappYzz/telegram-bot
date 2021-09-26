<?php


namespace App\Records;


use Boot\Database\record;

class statusRecord extends record {

    protected static $tableName = 'status';

    private $id;
    private $name;
    private $description;

    public function getID() {
        return $this->id;
    }
    public function getName() {
        return $this->name;
    }
    public function getDescription() {
        return $this->description;
    }
}