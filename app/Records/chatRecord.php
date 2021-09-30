<?php


namespace App\Records;

use Boot\Database\record;

class chatRecord extends record {

    protected static string $tableName = 'chat';
    protected static array $fillable = ['id', 'status_id', 'user_id'];

    protected int $id;
    protected int $status_id;
    protected int $user_id;

    public function getID(): int {
        return $this->id;
    }
    public function getStatusID(): int {
        return $this->status_id;
    }
    public function getUserID(): int {
        return $this->user_id;
    }
    public function setID($id): void {
        $this->id = $id;
    }
    public function setStatusID($status_id): void {
        $this->status_id = $status_id;
    }
    public function setUserID($user_id): void {
        $this->user_id = $user_id;
    }
}