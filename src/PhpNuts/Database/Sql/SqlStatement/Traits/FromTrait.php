<?php

namespace PhpNuts\Database\Sql\SqlStatement\Traits;

use PhpNuts\Database\Sql\SqlBlock;
use PhpNuts\Database\Sql\SqlBlock\FromBlock;
use PhpNuts\Database\Sql\SqlFragment;
use PhpNuts\Database\Sql\SqlKeyword;

/**
 * Trait FromTrait
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
trait FromTrait
{
    /**
     * @param string $sql
     * @param array|string|int|float|bool|null $parameters A single or array of scalar parameters
     * @return $this
     */
    public function andFrom(string $sql, $parameters = [])
    {
        return $this->addFragment(SqlKeyword::FROM, new SqlFragment($sql, $parameters));
    }

    /**
     * @param string $sql
     * @param array|string|int|float|bool|null $parameters A single or array of scalar parameters
     * @return $this
     */
    public function from(string $sql, $parameters = [])
    {
        $this->setBlock(SqlKeyword::FROM, new FromBlock());
        return $this->andFrom($sql, $parameters);
    }
}