<?php
class QueryBuilder
{
    private array $select = [];
    private string $from = '';
    private array $joins = [];
    private array $where = [];
    private string $groupBy = '';
    private array $groups = [];
    public array $params = [];
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function reset(): void
    {
        $this->select = [];
        $this->from = '';
        $this->joins = [];
        $this->where = [];
        $this->groupBy = '';
        $this->groups = [];
        $this->params = [];
    }

    public function select(...$columns): self
    {
        $this->select = $columns;
        return $this;
    }

    public function from(string $table): self
    {
        $this->from = $table;
        return $this;
    }

    public function leftJoin(string $table, string $condition): self
    {
        $this->joins[] = " LEFT JOIN $table ON $condition ";
        return $this;
    }

    public function rightJoin(string $table, string $condition): self
    {
        $this->joins[] = " RIGHT JOIN $table ON $condition ";
        return $this;
    }

    public function innerJoin(string $table, string $condition): self
    {
        $this->joins[] = " INNER JOIN $table ON $condition ";
        return $this;
    }

    public function outerJoin(string $table, string $condition): self
    {
        $this->joins[] = " OUTER JOIN $table ON $condition ";
        return $this;
    }

    public function where(string $condition): self
    {
        $this->where[] = $condition;
        return $this;
    }

    public function groupBy(string $column): self
    {
        $this->groupBy = $column;
        return $this;
    }

    public function group(string $column, string $alias): self
    {
        $this->groups[$column] = $alias;
        return $this;
    }

    public function build(): PDOStatement
    {
        $shouldGroup = count($this->groups) > 0 && !empty($this->groupBy);
        $groupColumns = $shouldGroup ? ', ' . implode(', ', array_map(function($column, $alias) {
                return "GROUP_CONCAT(DISTINCT $column SEPARATOR ', ') as $alias";
            }, array_keys($this->groups), array_values($this->groups))) : '';

        $sql = 'SELECT ' . implode(', ', $this->select)
            . $groupColumns
            . ' FROM ' . $this->from
            . implode(' ', $this->joins);

        // append all `WHERE`s joined by `AND`
        if (count($this->where) > 0) {
            $sql .= ' WHERE ' . implode(' AND ', $this->where);
        }

        // handle the group by specifier
        if (!empty($this->groupBy)) {
            $sql .= ' GROUP BY ' . $this->groupBy;
        }

        $sql .= ';';

        $query = $this->conn->prepare($sql);
        foreach ($this->params as $param => $value) {
            if (is_int($value)) {
                $type = PDO::PARAM_INT;
            } elseif (is_bool($value)) {
                $type = PDO::PARAM_BOOL;
            } elseif (is_null($value)) {
                $type = PDO::PARAM_NULL;
            } else {
                $type = PDO::PARAM_STR;
            }
            // can't pass $value directly because bindParam requires a reference, so we pass $this->params[$param]
            $query->bindParam($param, $this->params[$param], $type);
        }

        return $query;
    }
}
