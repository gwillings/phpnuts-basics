<?php

namespace PhpNuts\Database\Sql\SqlBlock;

use PhpNuts\Database\Sql\SqlBlock;

/**
 * Class DeleteBlock
 * @package PhpNuts\Database\Sql\SqlBlock
 */
class DeleteBlock extends SqlBlock
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
        return "DELETE " . parent::getSql();
    }
}