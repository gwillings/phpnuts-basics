<?php

namespace PhpNuts\Database\Sql\SqlBlock;

use PhpNuts\Database\Sql\SqlBlock;

/**
 * Class OrderByBlock
 * @package PhpNuts\Database\Sql\SqlBlock
 */
class OrderByBlock extends SqlBlock
{
    /**
     * OrderByBlock constructor.
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
        return "ORDER BY " . parent::getSql();
    }
}