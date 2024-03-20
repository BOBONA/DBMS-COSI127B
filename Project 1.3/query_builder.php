<?php

/**
 * Class QueryBuilder
 *
 * This class provides a fluent interface for building SQL queries using PDO in PHP.
 *
 * Usage Example:
 * $pdo = new PDO($dsn, $username, $password);
 * $queryBuilder = new QueryBuilder($pdo);
 *
 * $result = $queryBuilder->select('column1', 'column2')
 *                        ->from('table')
 *                        ->leftJoin('another_table', 'table.id = another_table.table_id')
 *                        ->where('column1 > 10')
 *                        ->groupBy('column2')
 *                        ->limit(10)
 *                        ->build()
 *                        ->execute();
 *
 * while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
 *     // Process each row
 * }
 *
 */
class QueryBuilder
{
    private array $select = [];
    private string $from = '';
    private array $joins = [];
    private array $where = [];
    private string $groupBy = '';
    private array $groups = [];
    private array $having = [];
    private string $orderBy = '';
    private int $limit = 0;
    public array $params = [];
    private PDO $conn;

    /**
     * QueryBuilder constructor.
     *
     * @param PDO $conn The PDO instance to use for database connections.
     */
    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    /**
     * Reset all query building properties to their initial state.
     */
    public function reset(): void
    {
        $this->select = [];
        $this->from = '';
        $this->joins = [];
        $this->where = [];
        $this->groupBy = '';
        $this->groups = [];
        $this->having = [];
        $this->orderBy = '';
        $this->limit = 0;
        $this->params = [];
    }

    /**
     * Specify the columns to select.
     *
     * @param mixed ...$columns The columns to select.
     * @return $this
     */
    public function select(...$columns): self
    {
        $this->select = $columns;
        return $this;
    }

    /**
     * Specify the table to select from.
     *
     * @param string $table The table name.
     * @return $this
     */
    public function from(string $table): self
    {
        $this->from = $table;
        return $this;
    }

    /**
     * Add a left join clause to the query.
     *
     * @param string $table The table to join.
     * @param string $condition The join condition.
     * @return $this
     */
    public function leftJoin(string $table, string $condition): self
    {
        $this->joins[] = " LEFT JOIN $table ON $condition ";
        return $this;
    }

    /**
     * Add a right join clause to the query.
     *
     * @param string $table The table to join.
     * @param string $condition The join condition.
     * @return $this
     */
    public function rightJoin(string $table, string $condition): self
    {
        $this->joins[] = " RIGHT JOIN $table ON $condition ";
        return $this;
    }

    /**
     * Add an inner join clause to the query.
     *
     * @param string $table The table to join.
     * @param string $condition The join condition.
     * @return $this
     */
    public function innerJoin(string $table, string $condition): self
    {
        $this->joins[] = " INNER JOIN $table ON $condition ";
        return $this;
    }

    /**
     * Add an outer join clause to the query.
     *
     * @param string $table The table to join.
     * @param string $condition The join condition.
     * @return $this
     */
    public function outerJoin(string $table, string $condition): self
    {
        $this->joins[] = " OUTER JOIN $table ON $condition ";
        return $this;
    }

    /**
     * Add a WHERE clause to the query.
     *
     * @param string $condition The condition to add to the WHERE clause.
     * @return $this
     */
    public function where(string $condition): self
    {
        $this->where[] = $condition;
        return $this;
    }

    /**
     * Specify the column for grouping.
     *
     * @param string $column The column to group by.
     * @return $this
     */
    public function groupBy(string $column): self
    {
        $this->groupBy = $column;
        return $this;
    }

    /**
     * Specify a column for grouping with an alias.
     *
     * @param string $column The column to group by.
     * @param string $alias The alias for the grouped column.
     * @return $this
     */
    public function groupCol(string $column, string $alias): self
    {
        $this->groups[$column] = $alias;
        return $this;
    }

    /**
     * Add a HAVING clause to the query.
     *
     * @param string $condition The condition to add to the HAVING clause.
     * @return $this
     */
    public function having(string $condition): self
    {
        $this->having[] = $condition;
        return $this;
    }

    /**
     * Specify the limit for the query.
     *
     * @param int $limit The maximum number of rows to return.
     * @return $this
     */
    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Specify the ordering for the query.
     *
     * @param string $column The column to order by.
     * @param string $direction The direction of ordering (ASC or DESC).
     * @return $this
     */
    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $this->orderBy = " ORDER BY $column $direction";
        return $this;
    }

    /**
     * Build the SQL query and return a PDOStatement object ready to be executed.
     *
     * @return PDOStatement The prepared statement ready to be executed.
     */
    public function build(): PDOStatement
    {
        // Handle the grouping columns
        $shouldGroup = count($this->groups) > 0 && !empty($this->groupBy);
        $groupColumns = $shouldGroup ? ', ' . implode(', ', array_map(function ($column, $alias) {
                return "GROUP_CONCAT(DISTINCT $column SEPARATOR ', ') as $alias";
            }, array_keys($this->groups), array_values($this->groups))) : '';

        // Handle the full SELECT FROM clause
        $sql = 'SELECT ' . implode(', ', $this->select)
            . $groupColumns
            . ' FROM ' . $this->from
            . implode(' ', $this->joins);

        // Append all `WHERE`s joined by `AND`
        if (count($this->where) > 0) {
            $sql .= ' WHERE ' . implode(' AND ', $this->where);
        }

        // Handle the GROUP BY specifier
        if (!empty($this->groupBy)) {
            $sql .= ' GROUP BY ' . $this->groupBy;
        }

        // Append all `HAVING`s joined by `AND`
        if (count($this->having) > 0) {
            $sql .= ' HAVING ' . implode(' AND ', $this->having);
        }

        // Handle the ORDER BY specifier
        if (!empty($this->orderBy)) {
            $sql .= $this->orderBy;
        }

        // Handle the LIMIT specifier
        if ($this->limit > 0) {
            $sql .= ' LIMIT ' . $this->limit;
        }

        $sql .= ';';

        // Add the parameters to the query
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
