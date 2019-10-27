<?php

namespace PhpNuts\Database\Sql\SqlBlock;

use PhpNuts\Database\Sql\SqlBlock;

/**
 * Class HavingBlock
 * @package PhpNuts\Database\Sql\SqlBlock
 */
class HavingBlock extends SqlBlock
{
    /**
     * HavingBlock constructor.
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
        return "HAVING " . parent::getSql();
    }
}