<?php

namespace PhpNuts\Database\Sql\SqlStatement;

use PHPUnit\Framework\TestCase;

/**
 * Class SelectStatementTest
 * @package PhpNuts\Database\Sql\SqlStatement
 */
class SelectStatementTest extends TestCase
{
    /**
     * @param string $value
     * @return string
     */
    private function reduceWhitespace(string $value): string
    {
        return preg_replace('/\s+/', ' ', $value);
    }

    /**
     * A basic native select
     */
    public function testBasicSelect()
    {
        $query = new SelectStatement();
        $query->select(1);
        $this->assertEquals('SELECT 1', $query->toDebugString());
    }

    /**
     * Test select a single column from a single table
     */
    public function testFrom()
    {
        $query = new SelectStatement();
        $query
            ->select('t1.name')
            ->from('employee AS t1');
        $result = $this->reduceWhitespace($query->toDebugString());
        $this->assertEquals('SELECT t1.name FROM employee AS t1', $result);
    }

    /**
     * Test non-linear multiple FROM table selections.
     */
    public function testMultipleFrom()
    {
        $query = new SelectStatement();
        $query
            ->select('t1.name')
            ->from('employee AS t1');

        $query
            ->andFrom('info AS t2')
            ->andSelect('t2.salary');

        $result = $this->reduceWhitespace($query->toDebugString());
        $this->assertEquals('SELECT t1.name, t2.salary FROM employee AS t1, info AS t2', $result);
    }

    /**
     * Test a JOIN
     */
    public function testJoin()
    {
        $query = new SelectStatement();
        $query
            ->select("user.*")
            ->from("user")
            ->join("user_credit AS credit ON credit.userId = user.id")
            ->andSelect("credit.amount");
        $result = $this->reduceWhitespace($query->toDebugString());
        $this->assertEquals('SELECT user.*, credit.amount FROM user JOIN user_credit AS credit ON credit.userId = user.id', $result);
    }

    /**
     * Test a WHERE block using conflicting named parameters.
     * This should work
     */
    public function testWhere()
    {
        $query = new SelectStatement();
        $query
            ->from('user')
            ->andWhere('accountId = :id', ['id' => 1])
            ->andWhere('id = :id', [':id' => 2]);


        $result = $this->reduceWhitespace($query->toDebugString());
        $this->assertEquals('SELECT * FROM user WHERE accountId = 1 AND id = 2', $result);
    }
}