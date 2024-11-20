<?php

namespace Boot\Database\Relations;

use Boot\Database\QueryBuilder;
use Boot\Database\Record;

abstract class Relation
{
    /**
     * @var QueryBuilder
     */
    public QueryBuilder $queryBuilder;

    /**
     * @param string $localTable
     * @param Record $related
     * @param string $relationName
     */
    public function __construct(
        protected string $localTable,
        protected Record $related,
        protected string $relationName,
    ) {
        $this->queryBuilder = $this->related->newQuery();
    }

    /**
     * Loads related data and adds it to the corresponding records
     * @param array $records
     * @return void
     */
    abstract public function load(array &$records): void;

    /**
     * Returns fields that should be retrieved from the parent table when dealing with given relation
     * @return array
     */
    abstract public function getLocalTableRelationColumns(): array;

    /**
     * Returns the key under which the resulting associated data should be stored in the resulting array
     * @return string
     */
    public function getRelationName(): string
    {
        return $this->relationName;
    }

    /**
     * @param array $items
     * @param string $key
     * @return array
     */
    protected function getUniqueKeys(array $items, string $key): array
    {
        return array_unique(array_column($items, $key));
    }

    /**
     * @param string $field
     * @param array $values
     * @return array
     */
    protected function loadRelated(string $field, array $values): array
    {
        return $this->queryBuilder->whereIn($field, $values)->get();
    }
}