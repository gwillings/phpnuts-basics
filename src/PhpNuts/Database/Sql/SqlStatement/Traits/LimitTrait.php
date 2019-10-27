<?php

namespace PhpNuts\Database\Sql\SqlStatement\Traits;

use PhpNuts\Database\Sql\SqlBlock;
use PhpNuts\Database\Sql\SqlBlock\LimitBlock;
use PhpNuts\Database\Sql\SqlFragment;
use PhpNuts\Database\Sql\SqlKeyword;

/**
 * Trait LimitTrait
 *
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
trait LimitTrait
{
    /**
     * @param int|null $limit
     * @param int $offset [optional] defaults to zero (0)
     * @return $this
     */
    public function limit(?int $limit, int $offset = 0)
    {
        $this->resetLimit();
        if (empty($limit)) {
            return $this;
        }
        return $this->addFragment(SqlKeyword::LIMIT, new SqlFragment("{$limit} OFFSET {$offset}"));
    }

    /**
     * @return $this
     */
    public function resetLimit()
    {
        return $this->setBlock(SqlKeyword::LIMIT, new LimitBlock());
    }
}