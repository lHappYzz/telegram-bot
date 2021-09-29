<?php

namespace Boot\Database;

use Boot\application;
use Boot\Database\DB;

/**
 * Class record represents a table record from a database. It is like a model
 * @package Boot\Database
 */
abstract class record {

    public function __construct() {

    }

    /**
     * Save record into database. Returns saved record
     * @return record
     */
    abstract public function save();

    /**
     * Returns an array of database record objects
     * @return array
     */
    public static function fetchAll() {
        $records = [];
        $db = DB::getInstance();

        $result = $db->query("SELECT * FROM `" . static::getTableName() . "`");
        while (($record = $result->fetch_object(static::class)) !== null) {
            $records[] = $record;
        }

        return $records;
    }

    /**
     * This function is need to know what table to use in record class.
     * So in every class that extends base class record must have their own field called
     * $tableName
     * @return string
     */
    protected static function getTableName() {
        return static::$tableName;
    }

    public function update($id) {

    }

    /**
     * Returns an object that represents table record identified by the $tableName field
     * @param $id
     * Database record identifier
     * @return static
     */
    public static function fetch($id) {
        $db = DB::getInstance();

        $result = $db->query("SELECT * FROM `" . static::getTableName() . "` WHERE id = " . $id);

        if ($result->num_rows < 1) {
            return new static();
        }

        return $result->fetch_object(static::class);
    }
}