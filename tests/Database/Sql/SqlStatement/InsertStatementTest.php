<?php

namespace PhpNuts\Database\Sql\SqlStatement;

use PHPUnit\Framework\TestCase;

/**
 * Class InsertStatementTest
 * @package PhpNuts\Database\Sql\SqlStatement
 */
class InsertStatementTest extends TestCase
{
    /**
     * @param string $value
     * @return string
     */
    private function reduceWhitespace(string $value)
    {
        return  preg_replace('/\s+/', ' ', $value);
    }

    /**
     *
     */
    public function testBasics()
    {
        $query = new InsertStatement();
        $query->into('t1');
        $result = $this->reduceWhitespace($query->toDebugString());
        $expect = "INSERT INTO t1";
        $this->assertEquals($expect, $result);
    }

    /**
     *
     */
    public function testSettingValues()
    {
        $query = new InsertStatement();
        $query->into('tag')
            ->set("accountId = ?", [7])
            ->andSet("type = ?, title = ?, alias = ?", ['tag', 'Test', 'test']);
        $result = $this->reduceWhitespace($query->toDebugString());
        $expect = "INSERT INTO tag SET accountId = 7, type = 'tag', title = 'Test', alias = 'test'";
        $this->assertEquals($expect, $result);
    }
}