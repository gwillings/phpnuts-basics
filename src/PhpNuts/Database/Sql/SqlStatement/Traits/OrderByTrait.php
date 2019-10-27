<?php

namespace PhpNuts\Database\Sql\SqlStatement\Traits;

use PhpNuts\Database\Sql\SqlBlock;
use PhpNuts\Database\Sql\SqlBlock\OrderByBlock;
use PhpNuts\Database\Sql\SqlFragment;
use PhpNuts\Database\Sql\SqlKeyword;

/**
 * Trait OrderByTrait
 * IMPORTANT: this trait should only be used in classes extending
 * the AbstractStatement class, which has the method below.
 * @see AbstractStatement
 *
 * @method $this addFragment(string $name, SqlFragment $fragment)
 * @method bool hasFragments(string $name)
 * @method $this setBlock(string $name, SqlBlock $sqlBlock)
 *
 * @package PhpNuts\Database\Sql\SqlStatement\Traits
 */
trait OrderByTrait
{
    /**
     * @param string $sql
     * @param array|string|int|float|bool|null $parameters A single or array of scalar parameters
     * @return $this
     */
    public function andOrderBy(string $sql, $parameters = [])
    {
        return $this->addFragment(SqlKeyword::ORDER_BY, new SqlFragment($sql, $parameters));
    }

    /**
     * @param string $sql
     * @param array|string|int|float|bool|null $parameters A single or array of scalar parameters
     * @return $this
     */
    public function orderBy(string $sql, $parameters = [])
    {
        return $this->resetOrderBy()->andOrderBy($sql, $parameters);
    }

    /**
     * @return $this
     */
    public function resetOrderBy()
    {
        return $this->setBlock(SqlKeyword::ORDER_BY, new OrderByBlock());
    }
}