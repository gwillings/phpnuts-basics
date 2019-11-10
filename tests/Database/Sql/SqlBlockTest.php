<?php

namespace PhpNuts\Database\Sql;

use PHPUnit\Framework\TestCase;

/**
 * Class SqlBlockTest
 *
 * Tests associated with building fragments of SQL code
 * within a SQL Block. A SqlBlock represents, for example,
 * a SELECT syntax block of a SELECT statement.
 *
 * @package PhpNuts\Database\Sql
 */
class SqlBlockTest extends TestCase
{
    /**
     * We're not going to test the full functionality of SqlModifiers here
     * as this should be done in independent tests. But, we should check that
     * array parameters operate as expected.
     */
    public function testArrayParameters()
    {
        $block = new SqlBlock(' AND ');
        $block->addFragment(new SqlFragment("(accountId = :accountId OR merchantId = :accountId)", ['accountId' => 7]));
        $block->addFragment(new SqlFragment('productId IN ?', [[1, 2, 3, 4]]));
        $expectedSql = '(accountId = ? OR merchantId = ?) AND productId IN (?, ?, ?, ?)';
        $expectedParams = [7, 7, 1, 2, 3, 4];
        $this->assertEquals($expectedSql, $block->getSql());
        $this->assertEquals($expectedParams, $block->getParameters());
    }

    /**
     * Simple instantiation test.
     */
    public function testConstruct()
    {
        $block = new SqlBlock(' ');
        $this->assertEquals(' ', $block->getSeparator());
    }

    /**
     * Here we need to ensure that duplicate named parameters do not conflict.
     * Each statement should be self-contained meaning duplicate named parameters
     * should never conflict with another statement.
     */
    public function testDuplicateNamedParameterConcatenation()
    {
        $block = new SqlBlock(', ');
        $block->addFragment(new SqlFragment("firstName = :var", ['var' => 'Geoff']));
        $block->addFragment(new SqlFragment('lastName = :var', ['var' => 'Willings']));
        $block->addFragment(new SqlFragment('company = :var', ['var' => 'Azexis']));
        $expectedSql = 'firstName = ?, lastName = ?, company = ?';
        $expectedParams = ['Geoff', 'Willings', 'Azexis'];
        $this->assertEquals($expectedSql, $block->getSql());
        $this->assertEquals($expectedParams, $block->getParameters());
    }

    /**
     * Test separation of fragments with multiple fragments
     * and chaining addFragment() method.
     */
    public function testMultipleFragments()
    {
        $block = new SqlBlock(' ');
        $block
            ->addFragment(new SqlFragment('id = ?', [1]))
            ->addFragment(new SqlFragment('name = ?', 'John'));
        // We don't care that this is not valid syntax in this test
        $this->assertEquals('id = ? name = ?', $block->getSql());
        $this->assertEquals(2, $block->length());
        $this->assertEquals(1, $block->getParameterAt(0));
        $this->assertEquals('John', $block->getParameterAt(1));
    }

    /**
     * Test an UPDATE style list of table columns to update where the
     * list of columns may be supplied as separate micro-statements.
     * We expect that the SqlBlock can concatenate each statement into
     * a comma separated list.
     */
    public function testMultipleStatementConcat()
    {
        $block = new SqlBlock(', ');
        $block->addFragment(new SqlFragment("firstName = ?", ['Geoff']));
        $block->addFragment(new SqlFragment('lastName = ?', ['Willings']));
        $block->addFragment(new SqlFragment('company = ?', ['Azexis']));
        $expectedSql = 'firstName = ?, lastName = ?, company = ?';
        $expectedParams = ['Geoff', 'Willings', 'Azexis'];
        $this->assertEquals($expectedSql, $block->getSql());
        $this->assertEquals($expectedParams, $block->getParameters());
    }

    /**
     * Test that adding a single fragment does not
     * result in padded output using the separator.
     */
    public function testOneFragment()
    {
        $block = new SqlBlock(' ');
        $block->addFragment(new SqlFragment('id = ?', 1));
        $this->assertEquals('id = ?', $block->getSql());
        $this->assertEquals(1, $block->getParameterAt(0));
    }
}