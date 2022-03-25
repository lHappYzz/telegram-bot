<?php

namespace Boot\Database;

use ReflectionClass;

/**
 * Class record represents a table record from a database
 */
abstract class record
{
    protected string $table;

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
        return self::query()->select()->get();
    }

    /**
     * This function is need to know what table to use in record class.
     * So in every class that extends base class record must have their own field called
     * $table
     * @return string
     */
    protected function getTableName(): string
    {
        return $this->table;
    }

    /**
     * Updates a record in the database
     * @return bool
     */
    public function update(): bool
    {
        return $this->newQuery()->where('id', $this->getID())->update($this->attributesToArray());
    }

    /**
     * Creates a record to the database
     * @return bool
     */
    public function create(): bool
    {
        return $this->newQuery()->insert($this->attributesToArray());
    }

    /**
     * Removes a record from the database
     * @return bool
     */
    public function delete(): bool
    {
        return $this->newQuery()->where('id', $this->getID())->delete();
    }

    /**
     * Returns an object that represents table record identified by the $tableName field
     * @param $id
     * @return record
     */
    public static function fetch($id): record
    {
        $arrayOfResults = self::query()->select()->where('id', $id)->get();

        return array_pop($arrayOfResults) ?? new static();
    }

    /**
     * Find multiple records by their primary ids
     * @param array $ids
     * @return array
     */
    public static function find(array $ids): array
    {
        return self::query()->whereIn('id', $ids)->get();
    }

    public static function query(): QueryBuilder
    {
        return (new static)->newQuery();
    }

    public function newQuery(): QueryBuilder
    {
        $queryBuilderInstance = QueryBuilder::getInstance();
        $queryBuilderInstance->init($this->getTableName(), static::class);

        return $queryBuilderInstance;
    }
}