<?php

namespace PhpNuts\Database\Sql\SqlStatement;

use PhpNuts\Database\Sql\SqlBlock;
use PhpNuts\Database\Sql\SqlFragment;
use PhpNuts\Database\Sql\SqlKeyword;
use PhpNuts\Database\Sql\SqlStatement\Traits\FromTrait;
use PhpNuts\Database\Sql\SqlStatement\Traits\JoinTrait;
use PhpNuts\Database\Sql\SqlStatement\Traits\LimitTrait;
use PhpNuts\Database\Sql\SqlStatement\Traits\OrderByTrait;
use PhpNuts\Database\Sql\SqlStatement\Traits\WhereTrait;

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