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
     * Simple instantiation test.
     */
    public function testConstruct()
    {
        $block = new SqlBlock(' ');
        $this->assertEquals(' ', $block->getSeparator());
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
}