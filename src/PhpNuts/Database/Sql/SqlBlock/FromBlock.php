<?php

namespace PhpNuts\Database\Sql\SqlBlock;

use PhpNuts\Database\Sql\SqlBlock;

/**
 * Class FromBlock
 * @package PhpNuts\Database\Sql\SqlBlock
 */
class FromBlock extends SqlBlock
{
    /**
     * FromBlock constructor.
     */
    public function __construct()
    {
        parent::__construct(', ');
    }

    /**
     * @return string
     */
    public function getSql(): string
    {
        return "FROM " . parent::getSql();
    }
}