<?php

namespace PhpNuts\Database\Sql\SqlStatement;

use PhpNuts\Database\Sql\SqlBlock\DeleteBlock;
use PhpNuts\Database\Sql\SqlBlock\FromBlock;
use PhpNuts\Database\Sql\SqlBlock\JoinBlock;
use PhpNuts\Database\Sql\SqlBlock\LimitBlock;
use PhpNuts\Database\Sql\SqlBlock\OrderByBlock;
use PhpNuts\Database\Sql\SqlBlock\WhereBlock;
use PhpNuts\Database\Sql\SqlFragment;
use PhpNuts\Database\Sql\SqlKeyword;
use PhpNuts\Database\Sql\SqlStatement\Traits\FromTrait;
use PhpNuts\Database\Sql\SqlStatement\Traits\JoinTrait;
use PhpNuts\Database\Sql\SqlStatement\Traits\LimitTrait;
use PhpNuts\Database\Sql\SqlStatement\Traits\OrderByTrait;
use PhpNuts\Database\Sql\SqlStatement\Traits\WhereTrait;

/**
 * Class DeleteStatement
 * @package PhpNuts\Database\Sql\SqlStatement
 */
class DeleteStatement extends AbstractStatement
{
    use FromTrait;
    use JoinTrait;
    use WhereTrait;
    use OrderByTrait;
    use LimitTrait;

    /**
     * DeleteStatement constructor.
     */
    public function __construct()
    {
        parent::__construct([
            SqlKeyword::DELETE      => new DeleteBlock(),
            SqlKeyword::FROM        => new FromBlock(),
            SqlKeyword::JOIN        => new JoinBlock(),
            SqlKeyword::WHERE       => new WhereBlock(),
            SqlKeyword::ORDER_BY    => new OrderByBlock(),
            SqlKeyword::LIMIT       => new LimitBlock()
        ]);
        $this->andDelete('');
    }

    /**
     * @param string $sql
     * @param array|string|int|float|bool|null $parameters A single or array of scalar parameters
     * @return $this
     */
    public function andDelete(string $sql, $parameters = []): DeleteStatement
    {
        return $this->addFragment(SqlKeyword::DELETE, new SqlFragment($sql, $parameters));
    }

    /**
     * @param string $sql
     * @param array|string|int|float|bool|null $parameters A single or array of scalar parameters
     * @return $this
     */
    public function delete(string $sql, $parameters = []): DeleteStatement
    {
        $this->setBlock(SqlKeyword::DELETE, new DeleteBlock());
        return $this->andDelete($sql, $parameters);
    }

    /**
     * Returns an array containing the names of the blocks in
     * order of priority/sequence.
     * @return string[]
     */
    protected function getBlockSequence(): array
    {
        return [
            SqlKeyword::DELETE,
            SqlKeyword::FROM,
            SqlKeyword::JOIN,
            SqlKeyword::WHERE,
            SqlKeyword::ORDER_BY,
            SqlKeyword::LIMIT
        ];
    }
}