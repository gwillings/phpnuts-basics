<?php

namespace PhpNuts\Database\Sql\SqlBlock;

use PhpNuts\Database\Sql\SqlBlock;

/**
 * Class SelectBlock
 * @package PhpNuts\Database\Sql\SqlBlock
 */
class SelectBlock extends SqlBlock
{
    /**
     * SelectBlock constructor.
     */
    public function __construct()
    {
        parent::__construct(", \n\t");
    }

    /**
     * @return string
     */
    public function getSql(): string
    {
        return "SELECT " . parent::getSql();
    }
}