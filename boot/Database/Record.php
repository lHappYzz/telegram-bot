<?php

namespace Boot\Database;

use Boot\Interfaces\Recordable;
use Boot\Traits\Helpers;
use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use LogicException;
use ReflectionObject;

/**
 * Class record represents a table record from a database
 */
abstract class Record
{
    use Helpers;

    protected string $table = '';

    protected array $customFields = [];

    protected string $boundedTelegramEntity = '';

    protected Recordable $telegramEntity;

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

    protected function getCustomFields(): array
    {
        return $this->customFields;
    }

    protected function getBoundTelegramEntity(): string
    {
        return $this->boundedTelegramEntity;
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
     * Updates a record in the database
     * @return bool
     */
    public function update(): bool
    {
        return $this->newQuery()->where('id', $this->telegramEntity->getId())->update($this->attributesToArray());
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
        return $this->newQuery()->where('id', $this->telegramEntity->getId())->delete();
    }

    /**
     * Returns an object that represents table record identified by the $tableName field
     * @param $id
     * @return ?static
     */
    public static function fetch($id): ?static
    {
        $arrayOfResults = self::query()->select()->where('id', $id)->get();

        return array_pop($arrayOfResults);
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
        $queryBuilderInstance = new QueryBuilder();

        $queryBuilderInstance->init($this->getTableName(), static::class);

        return $queryBuilderInstance;
    }

    public static function createFrom(Recordable $recordableEntity): static
    {
        $record = new static();

        $boundTelegramEntity = $record->getBoundTelegramEntity();
        if ($recordableEntity instanceof $boundTelegramEntity) {
            $record->telegramEntity = $recordableEntity;
            return $record;
        }

        throw new LogicException('Bounded telegram entity does not match passed entity.');
    }

    public function with(array $columnValues): static
    {
        foreach ($columnValues as $columnName => $columnValue) {
            if (in_array($columnName, $this->customFields, true)) {
                $this->$columnName = $columnValue;
            } else {
                throw new InvalidArgumentException('The $columnValues parameter must match records` custom fields array.');
            }
        }

        return $this;
    }

    /**
     * Checks if field is present into $fillable array
     * @param $field
     * @return bool
     */
    #[Pure] private function isFillable($field): bool
    {
        return in_array($field, $this->fillable, true);
    }

    /**
     * Returns an associative array where key is field name and value
     * represents field value
     * @return array
     */
    private function attributesToArray(): array
    {
        $arrayOfAttributes = [];

        if (isset($this->telegramEntity)) {
            $arrayOfAttributes = $this
                ->telegramEntity
                ->getArrayOfAttributes(array_diff($this->fillable, $this->getCustomFields()));
        }

        return array_merge($arrayOfAttributes, $this->getArrayOfAttributesFromCurrentRecord());
    }

    private function getArrayOfAttributesFromCurrentRecord(): array
    {
        $arrayOfAttributes = [];

        $recordReflection = new ReflectionObject($this);
        foreach ($recordReflection->getProperties() as $property) {
            if (!$property->isPrivate()) {
                $propertyName = $property->getName();
                if ($this->isFillable($propertyName)) {
                    $arrayOfAttributes[$propertyName] = $this->$propertyName;
                }
            }
        }

        return $arrayOfAttributes;
    }
}