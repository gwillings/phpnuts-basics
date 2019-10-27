<?php

namespace PhpNuts\Database\Sql\SqlStatement;

use PhpNuts\Database\Sql\SqlBlock\InsertBlock;
use PhpNuts\Database\Sql\SqlBlock\IntoBlock;
use PhpNuts\Database\Sql\SqlBlock\SetBlock;
use PhpNuts\Database\Sql\SqlFragment;
use PhpNuts\Database\Sql\SqlKeyword;
use PhpNuts\Database\Sql\SqlStatement\Traits\SetTrait;

/**
 * Class InsertStatement
 * @package PhpNuts\Database\Sql\SqlStatement
 */
class InsertStatement extends AbstractStatement
{
    use SetTrait;

    /**
     * InsertStatement constructor.
     */
    public function __construct()
    {
        parent::__construct([
            SqlKeyword::INSERT  => new InsertBlock(),
            SqlKeyword::INTO    => new IntoBlock(),
            SqlKeyword::SET     => new SetBlock()
        ]);
        $this->insert('');
    }

    /**
     * Returns an array containing the names of the blocks in
     * order of priority/sequence.
     * @return string[]
     */
    protected function getBlockSequence(): array
    {
        return [
            SqlKeyword::INSERT,
            SqlKeyword::INTO,
            SqlKeyword::SET
        ];
    }

    /**
     * @param string $sql
     * @param array|string|int|float|bool|null $parameters A single or array of scalar parameters
     * @return $this
     */
    public function insert(string $sql, $parameters = []): InsertStatement
    {
        $this->setBlock(SqlKeyword::INSERT, new InsertBlock());
        return $this->addFragment(SqlKeyword::INSERT, new SqlFragment($sql, $parameters));
    }

    /**
     * @param string $tableName
     * @return $this
     */
    public function into(string $tableName)
    {
        $this->setBlock(SqlKeyword::INTO, new IntoBlock());
        return $this->addFragment(SqlKeyword::INTO, new SqlFragment($tableName));
    }
}