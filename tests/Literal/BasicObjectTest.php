<?php

namespace PhpNuts\Literal;

use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Class BasicObjectTest
 * @package PhpNuts\Literal
 */
class BasicObjectTest extends TestCase
{
    /**
     * Test that passing an associative array is stored
     * and becomes available through the getProperties() method.
     */
    public function testProperties()
    {
        $obj = new BasicObject([
            'firstName' => 'Bob',
            'lastName' => 'Bobsworth',
            'age' => 20
        ]);
        $this->assertTrue(is_array($obj->getProperties()));
    }

    /**
     * Test that:
     * a) BasicObject::set() method works and that the value can be obtained by BasicObject::get()
     * b) Ensure that set and get magic methods achieve the same as (a).
     * c) Ensure that the isset() function works via magic methods for internal properties.
     */
    public function testGetSet()
    {
        $obj = new BasicObject();
        $obj->set('name', 'Bob');
        $this->assertEquals('Bob', $obj->get('name'));
        $obj->name = 'Steve';
        $this->assertEquals('Steve', $obj->name);
        $this->assertTrue(isset($obj->name));
    }

    /**
     * Test that the unset() function has access to remove properties.
     */
    public function testUnset()
    {
        $obj = new BasicObject();
        $obj->set('name', 'Bob');
        unset($obj->name);
        $this->assertFalse($obj->hasProperties());
    }

    /**
     * Test that a named property can be renamed and that the old name is no longer available.
     */
    public function testRename()
    {
        $obj = new BasicObject();
        $obj->set('name', 'Bob');
        $obj->rename('name', 'firstName');
        $this->assertFalse(isset($obj->name));
        $this->assertTrue(isset($obj->firstName));
    }

    /**
     * Test that a base object's properties can be extended by merging
     * data from another object. In this case we're ensuring that a stdClass()
     * can be merged into our associative array based object.
     */
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

    /**
     * Test that we can iterate across each element within our array
     * using a foreach() statement.
     */
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

    /**
     * Test that the object create a string when converting into a JSON string.
     * Test that the JSON is valid.
     */
    public function testToJson()
    {
        $obj = new BasicObject([
            'firstName' => 'John',
            'lastName' => 'Johnson'
        ]);
        $this->assertTrue(is_string($obj->toJson()));
        $this->assertTrue(!is_null(json_decode($obj->toJson())));
    }

    /**
     * Test that the magic method __call() allows us to gain get and set access
     * to internal properties.
     *
     * IMPORTANT: it is ok that our setFirstName() and getFirstName() methods
     * show as "not found", because...they don't exist. But, in an extending
     * class you should always declare your magic methods in PHP DocBlocks.
     */
    public function testGetterSetter()
    {
        $obj = new BasicObject([
            'firstName' => ''
        ]);
        $obj->setFirstName('John');
        $this->assertEquals('John', $obj->getFirstName());
    }
}