<?php

namespace PhpNuts\Database\Sql\SqlStatement\Traits;

use PhpNuts\Database\Sql\SqlBlock;
use PhpNuts\Database\Sql\SqlFragment;
use PhpNuts\Database\Sql\SqlKeyword;

/**
 * Trait JoinTrait
 * IMPORTANT: this trait should only be used in classes extending
 * the AbstractStatement class, which has the method below.
 * @see AbstractStatement
 *
 * The JoinTrait should is permitted on the following SqlStatement types:
 * @see DeleteStatement
 * @see SelectStatement
 * @see UpdateStatement
 *
 * @method $this addFragment(string $name, SqlFragment $fragment)
 * @method bool hasFragments(string $name)
 * @method $this setBlock(string $name, SqlBlock $sqlBlock)
 *
 * @package PhpNuts\Database\Sql\SqlStatement\Traits
 */
trait JoinTrait
{
    /**
     * @param string $sql
     * @param array|string|int|float|bool|null $parameters A single or array of scalar parameters
     * @return $this
     */
    protected function andJoin(string $sql, $parameters = [])
    {
        return $this->addFragment(SqlKeyword::JOIN, new SqlFragment($sql, $parameters));
    }

    /**
     * @param string $sql
     * @param array|string|int|float|bool|null $parameters A single or array of scalar parameters
     * @return $this
     */
    public function innerJoin(string $sql, $parameters = [])
    {
        return $this->andJoin("INNER JOIN " . $sql, $parameters);
    }

    /**
     * @param string $sql
     * @param array|string|int|float|bool|null $parameters A single or array of scalar parameters
     * @return $this
     */
    public function join(string $sql, $parameters = [])
    {
        return $this->andJoin("JOIN " . $sql, $parameters);
    }

    /**
     * @param string $sql
     * @param array|string|int|float|bool|null $parameters A single or array of scalar parameters
     * @return $this
     */
    public function leftJoin(string $sql, $parameters = [])
    {
        return $this->andJoin("LEFT JOIN " . $sql, $parameters);
    }
}