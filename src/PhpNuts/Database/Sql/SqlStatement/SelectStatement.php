<?php

namespace PhpNuts\Database\Sql\SqlStatement;

use PDO;
use PhpNuts\Database\Sql\SqlBlock;
use PhpNuts\Database\Sql\SqlFragment;
use PhpNuts\Database\Sql\SqlKeyword;
use PhpNuts\Database\Sql\SqlStatement\Traits\FromTrait;
use PhpNuts\Database\Sql\SqlStatement\Traits\JoinTrait;
use PhpNuts\Database\Sql\SqlStatement\Traits\LimitTrait;
use PhpNuts\Database\Sql\SqlStatement\Traits\OrderByTrait;
use PhpNuts\Database\Sql\SqlStatement\Traits\WhereTrait;
use stdClass;

/**
 * Class SelectStatement
 * @package PhpNuts\Database\Sql\SqlStatement
 */
class SelectStatement extends AbstractStatement
{
    use FromTrait;
    use JoinTrait;
    use WhereTrait;
    use OrderByTrait;
    use LimitTrait;

    /**
     * SelectStatement constructor.
     */
    public function __construct()
    {
        parent::__construct([
            SqlKeyword::SELECT      => new SqlBlock\SelectBlock(),
            SqlKeyword::FROM        => new SqlBlock\FromBlock(),
            SqlKeyword::JOIN        => new SqlBlock\JoinBlock(),
            SqlKeyword::WHERE       => new SqlBlock\WhereBlock(),
            SqlKeyword::GROUP_BY    => new SqlBlock\GroupByBlock(),
            SqlKeyword::HAVING      => new SqlBlock\HavingBlock(),
            SqlKeyword::ORDER_BY    => new SqlBlock\OrderByBlock(),
            SqlKeyword::LIMIT       => new SqlBlock\LimitBlock()
        ]);
        $this->select('*');
    }

    /**
     * @param string $sql
     * @param array|string|int|float|bool|null $parameters A single or array of scalar parameters
     * @return $this
     */
    public function andGroupBy(string $sql, $parameters = []): SelectStatement
    {
        return $this->addFragment(SqlKeyword::GROUP_BY, new SqlFragment($sql, $parameters));
    }

    /**
     * @param string $sql
     * @param array|string|int|float|bool|null $parameters A single or array of scalar parameters
     * @return $this
     */
    public function andHaving(string $sql, $parameters = []): SelectStatement
    {
        if ($this->hasFragments(SqlKeyword::HAVING)) {
            $sql = 'AND ' . $sql;
        }
        return $this->addFragment(SqlKeyword::HAVING, new SqlFragment($sql, $parameters));
    }

    /**
     * @param string $sql
     * @param array|string|int|float|bool|null $parameters A single or array of scalar parameters
     * @return $this
     */
    public function andSelect(string $sql, $parameters = []): SelectStatement
    {
        return $this->addFragment(SqlKeyword::SELECT, new SqlFragment($sql, $parameters));
    }

    /**
     * Fetches a single row of data or NULL if no result was found.
     * Data is represented as a stdClass or an array depending on the default Database fetch mode.
     * @return stdClass|array|null
     */
    public function fetch()
    {
        $statement = $this->createStatement();
        $statement->execute();
        $result = $statement->fetch();
        return ($result !== false) ? $result : null;
    }

    /**
     * Returns an array containing zero or more result set rows.
     * Data per array element will be represented as a stdClass or an array depending on the default Database fetch mode.
     * @return stdClass[]|array[]
     */
    public function fetchAll(): array
    {
        $statement = $this->createStatement();
        $statement->execute();
        return $statement->fetchAll();
    }

    /**
     * Returns a single column value as a scalar, or NULL if no results are found.
     * @param int $index [optional] The column index to fetch (zero-indexed). Defaults to 0 - the first column.
     * @return mixed|null
     */
    public function fetchColumn(int $index = 0)
    {
        $statement = $this->createStatement();
        $statement->execute();
        $result = $statement->fetchColumn($index);
        return ($result !== false) ? $result : null;
    }

    /**
     * Returns an array containing zero or more single column results.
     * @param int $index [optional] The column index to fetch (zero-indexed). Defaults to 0 - the first column.
     * @return array
     */
    public function fetchColumnAll(int $index = 0): array
    {
        $statement = $this->createStatement();
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_COLUMN, $index);
    }

    /**
     * Returns an array containing the names of the blocks in
     * order of priority/sequence.
     * @return string[]
     */
    protected function getBlockSequence(): array
    {
        return [
            SqlKeyword::SELECT,
            SqlKeyword::FROM,
            SqlKeyword::JOIN,
            SqlKeyword::WHERE,
            SqlKeyword::GROUP_BY,
            SqlKeyword::HAVING,
            SqlKeyword::ORDER_BY,
            SqlKeyword::LIMIT
        ];
    }

    /**
     * @param string $sql
     * @param array|string|int|float|bool|null $parameters A single or array of scalar parameters
     * @return $this
     */
    public function groupBy(string $sql, $parameters = []): SelectStatement
    {
        return $this->resetGroupBy()->andGroupBy($sql, $parameters);
    }

    /**
     * @param string $sql
     * @param array|string|int|float|bool|null $parameters A single or array of scalar parameters
     * @return $this
     */
    public function having(string $sql, $parameters = []): SelectStatement
    {
        return $this->resetHaving()->andHaving($sql, $parameters);
    }

    /**
     * @param string $sql
     * @param array|string|int|float|bool|null $parameters A single or array of scalar parameters
     * @return $this
     */
    public function orHaving(string $sql, $parameters = []): SelectStatement
    {
        if ($this->hasFragments(SqlKeyword::HAVING)) {
            $sql = "OR " . $sql;
        }
        return $this->addFragment(SqlKeyword::HAVING, new SqlFragment($sql, $parameters));
    }

    /**
     * @param string $sql
     * @param array|string|int|float|bool|null $parameters A single or array of scalar parameters
     * @return $this
     */
    public function select(string $sql, $parameters = []): SelectStatement
    {
        $this->setBlock(SqlKeyword::SELECT, new SqlBlock\SelectBlock());
        return $this->andSelect($sql, $parameters);
    }

    /**
     * @return $this
     */
    public function resetGroupBy(): SelectStatement
    {
        return $this->setBlock(SqlKeyword::GROUP_BY, new SqlBlock\GroupByBlock());
    }

    /**
     * @return $this
     */
    public function resetHaving(): SelectStatement
    {
        return $this->setBlock(SqlKeyword::HAVING, new SqlBlock\HavingBlock());
    }
}