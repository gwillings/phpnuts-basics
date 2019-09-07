<?php

namespace PhpNuts\Literal;

use PHPUnit\Framework\TestCase;

class BasicArrayTest extends TestCase
{

    public function testArrayAccess()
    {
        $myArray = new BasicArray();
        $myArray->push('one');
        $this->assertEquals('one', $myArray[0]);
    }

    public function testCount()
    {
        $myArray = new BasicArray();
        $myArray->push('one');
        $this->assertEquals(1, count($myArray));
    }

    public function testPush()
    {
        $myArray = new BasicArray();
        $this->assertEquals(0, $myArray->length());

        $myArray->push('one');
        $this->assertEquals(1, $myArray->length());
    }

}