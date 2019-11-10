<?php

namespace PhpNuts\Database\Sql\SqlStatement;

use PHPUnit\Framework\TestCase;

/**
 * Class UpdateStatementTest
 * @package PhpNuts\Database\Sql\SqlStatement
 */
class UpdateStatementTest extends TestCase
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
     *
     */
    public function testBasics()
    {
        $query = new UpdateStatement();
        $query->update("tag")
            ->andSet('title = ?', ['Test'])
            ->andSet('alias = ?', ['test'])
            ->where('id = ?', [2]);
        $result = $this->reduceWhitespace($query->toDebugString());
        $expect = "UPDATE tag SET title = 'Test', alias = 'test' WHERE id = 2";
        $this->assertEquals($expect, $result);
    }
}