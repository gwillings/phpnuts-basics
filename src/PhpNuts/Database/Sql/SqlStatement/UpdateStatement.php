<?php

namespace PhpNuts\Database\Sql\SqlStatement;

use PhpNuts\Database\Sql\SqlBlock\JoinBlock;
use PhpNuts\Database\Sql\SqlBlock\LimitBlock;
use PhpNuts\Database\Sql\SqlBlock\OrderByBlock;
use PhpNuts\Database\Sql\SqlBlock\SetBlock;
use PhpNuts\Database\Sql\SqlBlock\UpdateBlock;
use PhpNuts\Database\Sql\SqlBlock\WhereBlock;
use PhpNuts\Database\Sql\SqlFragment;
use PhpNuts\Database\Sql\SqlKeyword;
use PhpNuts\Database\Sql\SqlStatement\Traits\JoinTrait;
use PhpNuts\Database\Sql\SqlStatement\Traits\LimitTrait;
use PhpNuts\Database\Sql\SqlStatement\Traits\OrderByTrait;
use PhpNuts\Database\Sql\SqlStatement\Traits\SetTrait;
use PhpNuts\Database\Sql\SqlStatement\Traits\WhereTrait;

/**
 * Class UpdateStatement
 * @package PhpNuts\Database\Sql\SqlStatement
 */
class UpdateStatement extends AbstractStatement
{
    use JoinTrait;
    use WhereTrait;
    use SetTrait;
    use OrderByTrait;
    use LimitTrait;

    /**
     * UpdateStatement constructor.
     */
    public function __construct()
    {
        parent::__construct([
            SqlKeyword::UPDATE      => new UpdateBlock(),
            SqlKeyword::JOIN        => new JoinBlock(),
            SqlKeyword::SET         => new SetBlock(),
            SqlKeyword::WHERE       => new WhereBlock(),
            SqlKeyword::ORDER_BY    => new OrderByBlock(),
            SqlKeyword::LIMIT       => new LimitBlock()
        ]);
    }

    /**
     * @param string $sql
     * @param array|string|int|float|bool|null $parameters A single or array of scalar parameters
     * @return $this
     */
    public function andUpdate(string $sql, $parameters = []): UpdateStatement
    {
        return $this->addFragment(SqlKeyword::UPDATE, new SqlFragment($sql, $parameters));
    }

    /**
     * Executes the Update Statement and return the number of rows affected.
     * @return int
     */
    public function execute(): int
    {
        $statement = $this->createStatement();
        $statement->execute();
        return $statement->rowCount();
    }

    /**
     * Returns an array containing the names of the blocks in
     * order of priority/sequence.
     * @return string[]
     */
    protected function getBlockSequence(): array
    {
        return [
            SqlKeyword::UPDATE,
            SqlKeyword::JOIN,
            SqlKeyword::SET,
            SqlKeyword::WHERE,
            SqlKeyword::ORDER_BY,
            SqlKeyword::LIMIT
        ];
    }

    /**
     * @param string $sql
     * @param array|string|int|float|bool|null $parameters A single or array of scalar parameters
     * @return $this
     */
    public function update(string $sql, $parameters = []): UpdateStatement
    {
        $this->setBlock(SqlKeyword::UPDATE, new UpdateBlock());
        return $this->andUpdate($sql, $parameters);
    }
}