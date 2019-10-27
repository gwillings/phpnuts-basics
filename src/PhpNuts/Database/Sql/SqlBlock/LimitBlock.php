<?php

namespace PhpNuts\Database\Sql\SqlBlock;

use PhpNuts\Database\Sql\SqlBlock;

/**
 * Class LimitBlock
 * @package PhpNuts\Database\Sql\SqlBlock
 */
class LimitBlock extends SqlBlock
{
    /**
     * LimitBlock constructor.
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
        return "LIMIT " . parent::getSql();
    }
}