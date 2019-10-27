<?php

namespace PhpNuts\Database\Sql\SqlBlock;

use PhpNuts\Database\Sql\SqlBlock;

/**
 * Class WhereBlock
 * @package PhpNuts\Database\Sql\SqlBlock
 */
class WhereBlock extends SqlBlock
{
    /**
     * WhereBlock constructor.
     */
    public function __construct()
    {
        parent::__construct("\n\t");
    }

    /**
     * @return string
     */
    public function getSql(): string
    {
        return "WHERE " . parent::getSql();
    }
}