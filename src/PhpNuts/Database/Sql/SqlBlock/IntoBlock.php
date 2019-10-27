<?php

namespace PhpNuts\Database\Sql\SqlBlock;

use PhpNuts\Database\Sql\SqlBlock;

/**
 * Class IntoBlock
 * @package PhpNuts\Database\Sql\SqlBlock
 */
class IntoBlock extends SqlBlock
{
    /**
     * IntoBlock constructor.
     */
    public function __construct()
    {
        parent::__construct(' ');
    }

    /**
     * @return string
     */
    public function getSql(): string
    {
        return 'INTO ' . parent::getSql();
    }
}