<?php

namespace PhpNuts;

use PHPUnit\Framework\TestCase;

/**
 * Class PersonTest
 * @package PhpNuts
 */
class PersonTest extends TestCase
{
    /**
     * Test that an object which extend the Basic Object
     * with pre-defined properties uses get and set properly.
     * Note: this test was written to illustrate the importance of DocBlocks
     * which plan an important role in code validation and auto-completion.
     */
    public function testGetterSetter()
    {
        $person = new Person();
        $person->setFirstName('Bob');
        $this->assertEquals('Bob', $person->getFirstName());
    }
}