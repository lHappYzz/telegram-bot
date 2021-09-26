<?php


namespace App\Records;


use Boot\Database\record;

class chatRecord extends record {

    protected static $tableName = 'chat';

    private $id;
    private $status_id;
    private $user_id;

    public function __construct() {
        parent::__construct();
    }
    public function getID() {
        return $this->id;
    }
    public function getStatusID() {
        return $this->status_id;
    }
    public function getUserID() {
        return $this->user_id;
    }

}