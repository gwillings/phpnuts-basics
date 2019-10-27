<?php

namespace PhpNuts\Database\Sql\SqlBlock;

use PhpNuts\Database\Sql\SqlBlock;

/**
 * Class OrGroupBlock
 * @package PhpNuts\Database\Sql\SqlBlock
 */
class OrGroupBlock extends SqlBlock
{
    /**
     * OrGroupBlock constructor.
     */
    public function __construct()
    {
        parent::__construct(" OR ");
    }

    /**
     * @return string
     */
    public function getSql(): string
    {
        return '(' . parent::getSql() . ')';
    }
}