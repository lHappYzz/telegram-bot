<?php

namespace Boot\Database\Relations;

use Boot\Database\Record;

class BelongsToRelation extends Relation
{
    /**
     * @var string
     */
    private string $relationAlias = 'belongsToRelation';

    /**
     * @param string $foreignKey
     * @param string $relatedLocalKey
     * @param Record $related
     * @param string $localTable
     * @param string $relationName
     */
    public function __construct(
        protected string $foreignKey,
        protected string $relatedLocalKey,
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
            $this->relatedLocalKey,
            $this->getUniqueKeys($records, $this->relationAlias)
        );

        $loadedMap = [];
        foreach ($loaded as $item) {
            $loadedMap[$item->{$this->relatedLocalKey}] = $item;
        }

        foreach ($records as $record) {
            $record->{$this->getRelationName()} = $loadedMap[
                $record->{$this->relationAlias}
            ] ?? [];

            unset($record->{$this->relationAlias});
        }
    }

    /**
     * @return string[]
     */
    public function getLocalTableRelationColumns(): array
    {
        return [
            $this->localTable.'.'.$this->foreignKey.' AS '.$this->relationAlias,
        ];
    }
}