<?php

namespace PhpNuts\Database\Sql\SqlBlock;

use PhpNuts\Database\Sql\SqlBlock;

/**
 * Class GroupByBlock
 * @package PhpNuts\Database\Sql\SqlBlock
 */
class GroupByBlock extends SqlBlock
{
    /**
     * GroupByBlock constructor.
     */
    public function __construct()
    {
        parent::__construct(", ");
    }

    /**
     * @return string
     */
    public function getSql(): string
    {
        return "GROUP BY " . parent::getSql();
    }

}