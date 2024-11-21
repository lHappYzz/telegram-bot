<?php

namespace Boot\Database\Relations;

use Boot\Database\Record;

class HasManyRelation extends Relation
{
    /**
     * @var string
     */
    private string $relationAlias = 'hasManyRelation';

    /**
     * @param string $relatedForeignKey
     * @param string $localKey
     * @param Record $related
     * @param string $localTable
     * @param string $relationName
     */
    public function __construct(
        protected string $relatedForeignKey,
        protected string $localKey,
        Record $related,
        string $localTable,
        string $relationName,
    ) {
        parent::__construct($localTable, $related, $relationName);
    }

    /**
     * @inheritDoc
     */
    public function load(array &$records): void
    {
        $loaded = $this->loadRelated(
            $this->relatedForeignKey,
            $this->getUniqueKeys($records, $this->relationAlias)
        );

        $loadedMap = [];
        foreach ($loaded as $item) {
            $loadedMap[$item->{$this->relatedForeignKey}][] = $item;
        }

        foreach ($records as $record) {
            $record->{$this->getRelationName()} = $loadedMap[
                $record->{$this->relationAlias}
            ] ?? [];

            unset($record->{$this->relationAlias});
        }
    }

    /**
     * @inheritDoc
     */
    public function getLocalTableRelationColumns(): array
    {
        return [
            $this->localTable.'.'.$this->localKey.' AS '.$this->relationAlias,
        ];
    }
}