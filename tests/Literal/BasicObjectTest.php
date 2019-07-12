<?php

namespace PhpNuts\Literal;

use PHPUnit\Framework\TestCase;
use stdClass;

class BasicObjectTest extends TestCase
{

    public function testProperties()
    {
        $obj = new BasicObject([
            'firstName' => 'Bob',
            'lastName' => 'Bobsworth',
            'age' => 20
        ]);
        $this->assertTrue(is_array($obj->getProperties()));
    }

    public function testGetSet()
    {
        $obj = new BasicObject();
        $obj->set('name', 'Bob');
        $this->assertEquals('Bob', $obj->get('name'));

        $obj->name = 'Steve';
        $this->assertEquals('Steve', $obj->name);

        $this->assertTrue(isset($obj->name));
    }

    public function testUnset()
    {
        $obj = new BasicObject();
        $obj->set('name', 'Bob');
        unset($obj->name);
        $this->assertFalse($obj->hasProperties());
    }

    public function testRename()
    {
        $obj = new BasicObject();
        $obj->set('name', 'Bob');
        $obj->rename('name', 'firstName');
        $this->assertFalse(isset($obj->name));
        $this->assertTrue(isset($obj->firstName));
    }

    public function testMerge()
    {
        $obj = new BasicObject([
           'firstName' => '',
           'lastName' => ''
        ]);
        $std = new stdClass();
        $std->firstName = 'Bob';
        $obj->merge($std);
        $this->assertEquals('Bob', $obj->firstName);
    }

    public function testIterator()
    {
        $obj = new BasicObject([
            'firstName' => 'Steve',
            'lastName' => 'Stevenson'
        ]);
        $expected = ['firstName', 'lastName'];
        $index = 0;
        foreach ($obj as $propertyName => $value) {
            $this->assertEquals($expected[$index++], $propertyName);
        }
    }

    public function testToJson()
    {
        $obj = new BasicObject([
            'firstName' => 'John',
            'lastName' => 'Johnson'
        ]);
        $this->assertTrue(is_string($obj->toJson()));
    }

    public function testGetterSetter()
    {
        $obj = new BasicObject([
            'firstName' => ''
        ]);
        $obj->setFirstName('John');
        $this->assertEquals('John', $obj->getFirstName());
    }
}