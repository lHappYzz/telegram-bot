<?php

namespace Boot\Database;

//TODO: Use try catch when exception thrown
//TODO Updated: see composeUpdate, composeDelete and find out what to do in case when WHERE condition is empty
use JetBrains\PhpStorm\Pure;
use PDOStatement;

class  QueryBuilder
{
    /** @var boolean
     * NOTE: When using named marker for binding parameters (:parameter, :name)
     * query builder won't work properly if we have same column names,
     * because it causes same name of marks for PDO while it should be unique
     */
    private bool $isNamedMarker;

    private string $calledFromClass;

    private string $sqlType;
    private array $columns = [];
    private string $table;
    /**
     * @array array
     * [
     *      [
     *          'column' => (string) Table column name,
     *          'operator' => (string) Operator @see $comparisonOperators,
     *          'value' => (string) Value of column,
     *          'marker' => (string) Marker for sql parameter bindings @see formMarker,
     *          'boolean' => (string) May be "AND", "OR"
     *      ],
     * ]
     */
    private array $whereConditions = [];
    private array $bindings = [
        'insert' => [],
        'select' => [],
        'update' => [],
        'where' => [],
    ];

    private array $comparisonOperators = [
        '=', '>', '<', '>=', '<=', '<>',
    ];

    private array $logicalOperators = [
        'IN', 'AND', 'OR', 'BETWEEN', 'EXISTS', 'LIKE', 'NOT',
    ];

    public function init($tableName, $calledFrom): void
    {
        $this->table = $tableName;
        $this->calledFromClass = $calledFrom;
        $this->isNamedMarker = false;
    }

    public function update(array $values): bool
    {
        if (empty($values)) {
            return true;
        }

        $this->sqlType = 'UPDATE';

        $this->columns = array_keys($values);

        foreach ($values as $column => $value) {
            $this->addBinding($column, $value, 'update');
        }

        return $this->runQuery($this->composeUpdate())->errorCode() !== 00000;
    }

    public function insert(array $values): bool
    {
        if (empty($values)) {
            return true;
        }

        $this->sqlType = 'INSERT';

        $this->columns = array_keys($values);

        foreach ($values as $column => $value) {
            $this->addBinding($column, $value, 'insert');
        }

        return $this->runQuery($this->composeInsert())->errorCode() !== 00000;
    }

    public function delete(): bool
    {
        $this->sqlType = 'DELETE';

        return $this->runQuery($this->composeDelete())->errorCode() !== 00000;
    }

    public function select(array $columns = ['*']): QueryBuilder
    {
        if (empty($columns)) {
            $columns = ['*'];
        }

        $this->sqlType = 'SELECT';

        $this->columns = $columns;

        return $this;
    }

    public function where(string $column, string $operator = '=', $value = null, string $boolean = 'AND'): QueryBuilder
    {
        $this->addWhereCondition($column, $operator, $value, $boolean);

        return $this;
    }

    public function orWhere(string $column, string $operator = '=', string $value = null): QueryBuilder
    {
        $this->where($column, $operator, $value, 'OR');

        return $this;
    }

    public function whereIn(string $column, array $values): QueryBuilder
    {
        $this->where($column, 'IN', $values);

        return $this;
    }

    public function get(): array
    {
        $sql = $this->compose();
        $sqlResult = $this->runQuery($sql);

        $records = [];
        while (($record = $sqlResult->fetchObject($this->calledFromClass)) !== false) {
            $records[] = $record;
        }

        return $records;
    }

    public function toSql(bool $bindings = false): string
    {
        if ($bindings) {
            return $this->compose() . PHP_EOL . 'Bindings: ' . implode(',', $this->cleanBindings());
        }
        return $this->compose();
    }

    /**
     * Add new where condition that will be imploded into correct WHERE string
     * @see implodeWhereConditions
     * @param string $column
     * @param string $operator
     * @param ?mixed $value
     * @param ?string $boolean
     */
    private function addWhereCondition(string $column, string $operator, $value, ?string $boolean = 'AND'): void
    {
        if (!in_array($operator, array_merge($this->comparisonOperators, $this->logicalOperators), true)) {
            $value = $operator;
            $operator = '=';
        }

        if (is_array($value)) {
            $marker = $this->formMarker($column, count($value));
        } else {
            $marker = $this->formMarker($column);
        }

        $whereCondition = compact('column', 'operator', 'value', 'marker', 'boolean');
        $this->whereConditions[] = $whereCondition;

        $this->addBinding($column, $value, 'where');
    }

    /**
     * Add new value binding to bindings array
     * @param string $key
     * @param string|array $value
     * @param string $type
     */
    private function addBinding(string $key, $value, string $type): void
    {
        //PDO bad works with boolean so we will convert it to int type
        if (is_bool($value)) {
            $value = (int)$value;
        }

        if (is_array($value)) {
            $i = 0;
            foreach ($value as $item) {
                $this->isNamedMarker ?
                    $this->bindings[$type][$key.$i] = $item :
                    $this->bindings[$type][] = $item;
                $i++;
            }
        } else {
            $this->isNamedMarker ?
                $this->bindings[$type][$key] = $value :
                $this->bindings[$type][] = $value;
        }
    }

    /**
     * Form binding marker for PDO statement
     * @param string $column
     * @param int|null $count
     * @return string
     */
    private function formMarker(string $column, ?int $count = null): string
    {
        if ($count !== null) {
            $markers = [];
            for ($i = 0; $i < $count; $i++) {
                if ($this->isNamedMarker) {
                    $markers[] = $this->formMarker($column) . $i;
                } else {
                    $markers[] = $this->formMarker($column);
                }
            }

            return '(' . implode(',', $markers) . ')';
        }

        return $this->isNamedMarker ?
            ':' . trim($column)
            : '?';
    }

    /**
     * Here is sql string is composed according to sql type (SELECT, INSERT, UPDATE, DELETE)
     * @return string
     */
    private function compose(): string
    {
        $compose = 'compose' . ucfirst(strtolower($this->sqlType));

        return $this->$compose();
    }

    private function composeSelect(): string
    {
        $select = implode(', ', $this->columns);

        $where = $this->implodeWhereConditions();

        return "SELECT $select FROM $this->table $where";
    }

    private function composeDelete(): string
    {
        $where = $this->implodeWhereConditions();

        /*if (empty($where)) {
            throw new Exception('Unknown record to delete');
        }*/

        return "DELETE FROM $this->table $where";
    }

    private function composeInsert(): string
    {
        $insert = implode(', ', $this->columns);
        $values = str_repeat('?,', count($this->bindings['insert']));
        $values = substr($values, 0,-1);

        return "INSERT INTO $this->table ($insert) VALUES ($values)";
    }

    private function composeUpdate(): string
    {
        $set = implode(', ', array_map(static function ($element) {
            return $element . ' = ?';
        }, $this->columns));

        $where = $this->implodeWhereConditions();

        /*if (empty($where)) {
            throw new Exception('Unknown record to update.');
        }*/

        return "UPDATE $this->table SET $set $where";
    }

    /**
     * Implode created array of where conditions into correct sql WHERE string
     * @return string
     */
    private function implodeWhereConditions(): string
    {
        $result = '';

        if (!empty($this->whereConditions)) {
            $result .= 'WHERE ';

            $result .= implode(' ', array_map(static function ($element) {
                return $element['boolean'] . ' ' . $element['column'] . ' ' . $element['operator'] . ' ' . $element['marker'];
            }, $this->whereConditions));

            $result = preg_replace('/and |or /i', '', $result, 1);
        }

        return $result;
    }

    #[Pure] private function cleanBindings(): array
    {
        return array_merge($this->bindings[strtolower($this->sqlType)] ?? [],
            $this->sqlType !== 'INSERT' ?
                $this->bindings['where'] :
                []
        );
    }

    /**
     * Run SQL query using DB class functionality
     * @see DB::query()
     * @param string $query
     * @return PDOStatement
     */
    private function runQuery(string $query): PDOStatement
    {
        return DB::getInstance()->query($query, $this->cleanBindings());
    }
}