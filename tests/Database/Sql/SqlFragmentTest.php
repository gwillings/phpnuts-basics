<?php

namespace PhpNuts\Database\Sql;

use PHPUnit\Framework\TestCase;

/**
 * Class SqlFragmentTest
 * @package PhpNuts\Database\Sql
 */
class SqlFragmentTest extends TestCase
{
    /**
     * Test that the construct works with a single parameter
     * which should be rewritten as the only element in the parameters array.
     */
    public function testConstruct()
    {
        $fragment = new SqlFragment("id = ?", 1);
        $this->assertEquals("id = ?", $fragment->getSql());
        $this->assertEquals(1, count($fragment->getParameters()));
        $this->assertEquals(1, $fragment->getParameterAt(0));
    }

    /**
     * Test that debug string works:
     * 1. with an integer
     * 2. with a string
     * 3. with a NULL value
     * 4. with a boolean
     */
    public function testDebugString()
    {
        // 1. with an integer
        $fragment = new SqlFragment("id = ?", 1);
        $this->assertEquals("id = ?", $fragment->getSql());
        $this->assertEquals("id = 1", $fragment->toDebugString());

        // 2. with a string
        $fragment = new SqlFragment("name LIKE ?", ['John']);
        $this->assertEquals("name LIKE ?", $fragment->getSql());
        $this->assertEquals("name LIKE 'John'", $fragment->toDebugString());
        $this->assertEquals('John', $fragment->getParameterAt(0));

        // 3. with a NULL value
        $fragment = new SqlFragment("id IN ?", [[null, 1, 2, 3]]);
        $this->assertEquals("id IN (?, ?, ?, ?)", $fragment->getSql());
        $this->assertEquals("id IN (NULL, 1, 2, 3)", $fragment->toDebugString());
        $this->assertNull($fragment->getParameterAt(0));
        $this->assertEquals(1, $fragment->getParameterAt(1));
        $this->assertEquals(2, $fragment->getParameterAt(2));
        $this->assertEquals(3, $fragment->getParameterAt(3));

        // 4. with a boolean
        $fragment = new SqlFragment("id = ?", [true]);
        $this->assertEquals("id = ?", $fragment->getSql());
        $this->assertEquals("id = TRUE", $fragment->toDebugString());
        $this->assertTrue($fragment->getParameterAt(0));
    }

    /**
     * Test that named parameters are converted into query parameters.
     * This allows us to use SqlFragments with both PDO and MySQLi.
     * 1. with a single named parameter
     * 2. with multiple instances of a single named parameter
     * 3. with multiple unordered named parameters
     */
    public function testNamedParameter()
    {
        // 1. with a single named parameter
        $fragment = new SqlFragment("id = :id", [':id' => 1]);
        $this->assertEquals("id = ?", $fragment->getSql());
        $this->assertEquals("id = 1", $fragment->toDebugString());

        // 2. with multiple instances of a single named parameter
        $fragment = new SqlFragment("(accountId = :id OR merchantId = :id)", [':id' => 1]);
        $this->assertEquals("(accountId = ? OR merchantId = ?)", $fragment->getSql());
        $this->assertEquals("(accountId = 1 OR merchantId = 1)", $fragment->toDebugString());

        // 3. with multiple unordered named parameters
        $fragment = new SqlFragment("name = :name AND id = :id", [':id' => 1, ':name' => 'John']);
        $this->assertEquals("name = ? AND id = ?", $fragment->getSql());
        $this->assertEquals("name = 'John' AND id = 1", $fragment->toDebugString());
    }

    /**
     * Test array based parameters.
     * 1. A named based parameter
     * 2. A query based parameter
     */
    public function testNamedArray()
    {
        // 1. A named based parameter
        $fragment = new SqlFragment("id IN :id", [':id' => [1, 2, 3]]);
        $this->assertEquals("id IN (1, 2, 3)", $fragment->toDebugString());

        // 2. A query based parameter
        $fragment = new SqlFragment("id IN ?", [[1, 2, 3]]);
        $this->assertEquals("id IN (1, 2, 3)", $fragment->toDebugString());
    }
}