<?php

namespace PhpNuts\Database\Sql\SqlBlock;

use PhpNuts\Database\Sql\SqlBlock;

/**
 * Class InsertBlock
 * @package PhpNuts\Database\Sql\SqlBlock
 */
class InsertBlock extends SqlBlock
{
    /**
     * InsertBlock constructor.
     */
    public function __construct()
    {
        parent::__construct(" ");
    }

    /**
     * @return string
     */
    public function getSql(): string
    {
        return "INSERT " . parent::getSql();
    }
}