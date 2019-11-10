<?php

namespace PhpNuts\Database\Sql\SqlStatement;

use PHPUnit\Framework\TestCase;

/**
 * Class DeleteStatementTest
 * @package PhpNuts\Database\Sql\SqlStatement
 */
class DeleteStatementTest extends TestCase
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
     * Test a basic delete
     */
    public function testBasics()
    {
        $query = new DeleteStatement();
        $query->from("t1")
            ->where("id = ?", [1]);

        $expect = "DELETE FROM t1 WHERE id = 1";
        $result = $this->reduceWhitespace($query->toDebugString());
        $this->assertEquals($expect, $result);
    }
}