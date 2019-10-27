<?php

namespace PhpNuts\Database\Sql\SqlBlock;

use PhpNuts\Database\Sql\SqlBlock;

/**
 * Class UpdateBlock
 * @package PhpNuts\Database\Sql\SqlBlock
 */
class UpdateBlock extends SqlBlock
{
    /**
     * UpdateBlock constructor.
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
        return 'UPDATE ' . parent::getSql();
    }
}