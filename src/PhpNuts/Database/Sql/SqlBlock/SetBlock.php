<?php

namespace PhpNuts\Database\Sql\SqlBlock;

use PhpNuts\Database\Sql\SqlBlock;

/**
 * Class SetBlock
 * @package PhpNuts\Database\Sql\SqlBlock
 */
class SetBlock extends SqlBlock
{
    /**
     * SetBlock constructor.
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
        return 'SET ' . parent::getSql();
    }
}