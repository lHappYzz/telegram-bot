<?php

namespace Boot\Database;

use Boot\Database\DB;
use ReflectionClass;

/**
 * Class record represents a table record from a database
 */
abstract class record
{

    /**
     * Returns the identifier of the record
     * @return int
     */
    abstract public function getID(): int;

    /**
     * Checks if field is present into $fillable array
     * @param $field
     * @return bool
     */
    private function isFillable($field): bool
    {
        return in_array($field, static::$fillable, true);
    }

    /**
     * Returns an associative array where key is field name and value
     * represents field value
     * @return array
     */
    private function attributesToArray(): array
    {
        $class = new ReflectionClass($this);

        $arrayOfAttributes = [];
        foreach ($class->getProperties() as $property) {
            $propertyName = $property->getName();
            if ($this->isFillable($propertyName)) {
                $arrayOfAttributes[$propertyName] = $this->$propertyName;
            }
        }
        return $arrayOfAttributes;
    }

    /**
     * Returns an array of database record objects
     * @return array
     */
    public static function fetchAll(): array
    {
        $records = [];
        $db = DB::getInstance();

        $result = $db->query("SELECT * FROM `" . self::getTableName() . "`");
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
    protected static function getTableName(): string
    {
        return static::$tableName;
    }

    /**
     * Updates a record in the database
     * @return true
     */
    public function update(): bool
    {
        $db = DB::getInstance();
        $table = self::getTableName();
        $id = $this->getID();

        $propsToImplode = [];
        foreach ($this->attributesToArray() as $field => $value) {
            $propsToImplode[] = "$field = '{$value}'";
        }
        $field_value = implode(', ', $propsToImplode);

        $sql = "UPDATE $table SET $field_value WHERE id = '$id'";

        return $db->query($sql);
    }

    /** Creates a record to the database
     * @return true
     */
    public function create(): bool
    {
        $db = DB::getInstance();
        $table = self::getTableName();
        $fillableFields = implode(',', static::$fillable);

        $valuesToImplode = [];
        foreach ($this->attributesToArray() as $value) {
            if ($value) {
                $valuesToImplode[] = "'{$value}'";
            }
        }
        $stringOfValues = implode(', ', $valuesToImplode);

        $sql = "INSERT INTO $table($fillableFields) VALUES ($stringOfValues)";

        return $db->query($sql);
    }

    /**
     * Removes a record from the database
     * @return true
     */
    public function delete(): bool
    {
        $db = DB::getInstance();
        $table = self::getTableName();
        $id = $this->getID();

        $sql = "DELETE FROM $table WHERE id = '{$id}'";

        return $db->query($sql);
    }

    /**
     * Returns an object that represents table record identified by the $tableName field
     * @param $id
     * Database record identifier
     * @return record
     */
    public static function fetch($id): record
    {
        $db = DB::getInstance();

        $result = $db->query("SELECT * FROM `" . static::getTableName() . "` WHERE id = " . $id);

        if ($result->num_rows < 1) {
            return new static();
        }

        return $result->fetch_object(static::class);
    }

    /**
     * Returns an array of objects filtered by the passed conditions
     * @param array $options
     * @return array
     */
    public static function find($options = []): array
    {
        $db = DB::getInstance();
        $tableName = static::getTableName();

        $query = "SELECT * FROM $tableName";

        $whereConditions = [];
        if (!empty($options)) {
            foreach ($options as $key => $value) {
                $whereConditions[] = "{$key} = '{$value}'";
            }
            $whereClause = " WHERE " . implode(' AND ',$whereConditions);
            $query .= $whereClause;
        }

        $raw = $db->query($query);

        $result = [];
        foreach ($raw as $rawRow) {
            $result[] = self::fetch($rawRow['id']);
        }

        return $result;
    }

    public function query(): QueryBuilder
    {
        $queryBuilderInstance = QueryBuilder::getInstance();
        $queryBuilderInstance->init(static::getTableName(), static::class);

        return $queryBuilderInstance;
    }
}