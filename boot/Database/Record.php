<?php

namespace Boot\Database;

use Boot\Database\Relations\BelongsToRelation;
use Boot\Database\Relations\HasManyRelation;
use Boot\Interfaces\Recordable;
use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use LogicException;
use ReflectionObject;

/**
 * Class record represents a table record from a database
 */
abstract class Record
{
    /**
     * @var string
     */
    protected string $table = '';

    /**
     * @var array List of fields that are going to be inserted into table
     * when calling an update or create methods on record
     */
    protected array $fillable = [];

    /**
     * @var array List of fields that will be ignored when
     * trying to create record from telegram entity
     * @see boundedTelegramEntity
     * @see createFrom
     * @see with
     */
    protected array $customFields = [];

    /**
     * @var string
     */
    protected string $boundedTelegramEntity = '';

    /**
     * @var Recordable
     */
    protected Recordable $telegramEntity;

    /**
     * This function is need to know what table to use in record class.
     * So in every class that extends base class record must have their own field called
     * $table
     * @return string
     */
    public function getTableName(): string
    {
        return $this->table;
    }

    /**
     * @return array
     */
    protected function getCustomFields(): array
    {
        return $this->customFields;
    }

    /**
     * @return string
     */
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

    /**
     * @see self::newQuery()
     * @return QueryBuilder
     */
    public static function query(): QueryBuilder
    {
        return (new static)->newQuery();
    }

    /**
     * Creates a new query QueryBuilder instance for the called Record
     * @return QueryBuilder
     */
    public function newQuery(): QueryBuilder
    {
        /** @var QueryBuilder $queryBuilderInstance */
        $queryBuilderInstance = container(QueryBuilder::class);

        $queryBuilderInstance->init($this);

        return $queryBuilderInstance;
    }

    /**
     * Creates new DB record using telegramEntity
     * @param Recordable $recordableEntity
     * @return static
     */
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

    /**
     * Used to initialize record's fields that listed in customFields array
     * @see self::customFields
     * @param array $columnValues
     * @return $this
     */
    public function with(array $columnValues): static
    {
        foreach ($columnValues as $columnName => $columnValue) {
            if ($this->isCustom($columnName)) {
                $this->$columnName = $columnValue;
            } else {
                throw new InvalidArgumentException('The $columnValues parameter must match record\'s custom fields array.');
            }
        }

        return $this;
    }

    /**
     * Creates new inverted one-to-many relation
     * @param string $relatedAbstract
     * @param string $foreignKey
     * @param string $relatedLocalKey
     * @return BelongsToRelation
     */
    public function belongsTo(string $relatedAbstract, string $foreignKey, string $relatedLocalKey): BelongsToRelation
    {
        /** @var static $instance */
        $instance = container($relatedAbstract);

        $relationName = array_last(debug_backtrace(limit: 2))['function'];

        return new BelongsToRelation(
            $foreignKey,
            $relatedLocalKey,
            $instance,
            $this->getTableName(),
            $relationName
        );
    }

    /**
     * Creates new one-to-many relation
     * @param string $relatedAbstract
     * @param string $relatedForeignKey
     * @param string $localKey
     * @return HasManyRelation
     */
    public function hasMany(string $relatedAbstract, string $relatedForeignKey, string $localKey): HasManyRelation
    {
        /** @var static $instance */
        $instance = container($relatedAbstract);

        $relationName = array_last(debug_backtrace(limit: 2))['function'];

        return new HasManyRelation(
            $relatedForeignKey,
            $localKey,
            $instance,
            $this->getTableName(),
            $relationName
        );
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

    private function isCustom($field): bool
    {
        return in_array($field, $this->customFields, true);
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

    /**
     * Retrieves all public properties of static Record
     * @return array
     */
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