<?php

namespace PhpNuts\Database\Sql\SqlStatement;

use PHPUnit\Framework\TestCase;

/**
 * Class SelectStatementTest
 * @package PhpNuts\Database\Sql\SqlStatement
 */
class SelectStatementTest extends TestCase
{

    private function createBasicStatement()
    {
        $query = new SelectStatement();
        $query->select('user.*');
        $query->from('user');
        $query->where('user.id = ?', [1]);
        return $query;
    }

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
     *
     */
    public function testBasicSelectWhere()
    {
        $query = $this->createBasicStatement();
        $expect = 'SELECT user.* FROM user WHERE user.id = 1';
        $result = $this->reduceWhitespace($query->toDebugString());
        $this->assertEquals($expect, $result);
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
    public function testFromMultiple()
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
     *
     */
    public function testGroupBy()
    {
        $query = new SelectStatement();
        $query->select("userId, COUNT(userId) AS totalLogins")
            ->from("user_login")
            ->groupBy("id");
        $expect = "SELECT userId, COUNT(userId) AS totalLogins FROM user_login GROUP BY id";
        $result = $this->reduceWhitespace($query->toDebugString());
        $this->assertEquals($expect, $result);
    }

    /**
     *
     */
    public function testHaving()
    {
        $query = new SelectStatement();
        $query->select('*')
            ->from('user')
            ->having('id = ?', [1]);
        $expect = "SELECT * FROM user HAVING id = 1";
        $result = $this->reduceWhitespace($query->toDebugString());
        $this->assertEquals($expect, $result);
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
     *
     */
    public function testOrderBy()
    {
        $query = new SelectStatement();
        $query->select("*")
            ->from("user")
            ->orderBy('firstName ASC')
            ->andOrderBy('lastName ASC');
        $expect = "SELECT * FROM user ORDER BY firstName ASC, lastName ASC";
        $result = $this->reduceWhitespace($query->toDebugString());
        $this->assertEquals($expect, $result);
    }

    /**
     * This tests that SQL block building does not rely
     * on the sequence you add blocks.
     */
    public function testOutOfSequence()
    {
        $query = new SelectStatement();
        $query->from('user');
        $query->select('*');
        $expect = "SELECT * FROM user";
        $result = $this->reduceWhitespace($query->toDebugString());
        $this->assertEquals($expect, $result);
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

    /**
     * Test that named parameters are scoped to their own block.
     */
    public function testConflictingNamedParameters()
    {
        $query = new SelectStatement();
        $query->select('*')
            ->from('user')
            ->andWhere('id = :id', ['id' => 1])
            ->orWhere('id = :id', ['id' => 2]);

        $expect = "SELECT * FROM user WHERE id = 1 OR id = 2";
        $result = $this->reduceWhitespace($query->toDebugString());
        $this->assertEquals($expect, $result);
    }
}