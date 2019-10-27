<?php

namespace PhpNuts\Database\Sql\SqlBlock;

use PhpNuts\Database\Sql\SqlBlock;

/**
 * Class AndGroupBlock
 * @package PhpNuts\Database\Sql\SqlBlock
 */
class AndGroupBlock extends SqlBlock
{
    /**
     * AndGroupBlock constructor.
     */
    public function __construct()
    {
        parent::__construct(" AND ");
    }

    /**
     * @return string
     */
    public function getSql(): string
    {
        return '(' . parent::getSql() . ')';
    }
}