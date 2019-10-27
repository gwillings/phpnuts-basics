<?php

namespace PhpNuts\Database\Sql\SqlStatement\Traits;

use PhpNuts\Database\Sql\SqlBlock;
use PhpNuts\Database\Sql\SqlBlock\WhereBlock;
use PhpNuts\Database\Sql\SqlFragment;
use PhpNuts\Database\Sql\SqlKeyword;

/**
 * Trait WhereTrait
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
trait WhereTrait
{
    /**
     * @param string $sql
     * @param array|string|int|float|bool|null $parameters A single or array of scalar parameters
     * @return $this
     */
    public function andWhere(string $sql, $parameters = [])
    {
        if ($this->hasFragments(SqlKeyword::WHERE)) {
            $sql = "AND " . $sql;
        }
        return $this->addFragment(SqlKeyword::WHERE, new SqlFragment($sql, $parameters));
    }

    /**
     * @param string $sql
     * @param array|string|int|float|bool|null $parameters A single or array of scalar parameters
     * @return $this
     */
    public function orWhere(string $sql, $parameters = [])
    {
        if ($this->hasFragments(SqlKeyword::WHERE)) {
            $sql = "OR " . $sql;
        }
        return $this->addFragment(SqlKeyword::WHERE, new SqlFragment($sql, $parameters));
    }

    /**
     * @return $this
     */
    public function resetWhere()
    {
        return $this->setBlock(SqlKeyword::WHERE, new WhereBlock());
    }

    /**
     * @param string $sql
     * @param array|string|int|float|bool|null $parameters A single or array of scalar parameters
     * @return $this
     */
    public function where(string $sql, $parameters = [])
    {
        return $this->resetWhere()->andWhere($sql, $parameters);
    }
}