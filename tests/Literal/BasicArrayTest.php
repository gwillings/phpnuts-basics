<?php

namespace PhpNuts\Literal;

use PHPUnit\Framework\TestCase;

/**
 * Class BasicArrayTest
 * @package PhpNuts\Literal
 */
class BasicArrayTest extends TestCase
{
    /**
     * Assert that ArrayAccess interface allows us to access
     * elements within a BasicArray using brackets and index
     * just like you would on a primitive array.
     *
     * 1. Access elements using square bracket notation
     * 2. Ensure isset() works with square bracket notation
     * 3. Check that unset() removes elements by their index
     */
    public function testArrayAccess()
    {
        $myArray = new BasicArray();
        $myArray->push('one');
        // 1. Access elements using square bracket notation
        $this->assertEquals('one', $myArray[0]);
        // This is the equivalent of:
        $this->assertEquals('one', $myArray->get(0));
        // 2. Ensure isset() works with square bracket notation
        $this->assertTrue(isset($myArray[0]));
        $this->assertFalse(isset($myArray[1]));
        // 3. Check that unset() removes elements by their index
        unset($myArray[0]);
        $this->assertEquals(0, $myArray->length());
    }

    /**
     * Assert that the contains() returns effective results.
     */
    public function testContains()
    {
        $myArray = new BasicArray([
            'one', 'two', 'three'
        ]);
        $this->assertTrue($myArray->contains('two'));
        $this->assertFalse($myArray->contains('four'));
    }

    /**
     * Assert that Countable interface allows us to
     * use the count() function on our BasicArray.
     */
    public function testCount()
    {
        $myArray = new BasicArray();
        $myArray->push('one');
        $this->assertEquals(1, count($myArray));
    }

    /**
     * Assert that filtering with a callback function reduces the array
     * as expected.
     */
    public function testFilter()
    {
        $myArray = new BasicArray([
            1, 2, 3, 4, 5, 6, 7, 8, 9
        ]);
        $myArray2 = $myArray->filter(function ($index, $value) {
            return ($index % 2 === 0);
        });
        $this->assertEquals(5, $myArray2->length());
        $this->assertEquals(1, $myArray2->first());
        $this->assertEquals(9, $myArray2->last());
    }

    /**
     * Assert that BasicArray::first() returns either the first element
     * within the array or NULL if the array is empty.
     */
    public function testFirst()
    {
        $myArray = new BasicArray();
        $this->assertNull($myArray->first());
        $myArray->push('one');
        $this->assertEquals('one', $myArray->first());
    }

    /**
     * Assert that BasicArray::indexOf() returns the expected
     * index of a matching element.
     */
    public function testIndexOf()
    {
        $myArray = new BasicArray([
            'one', 'two', 'three'
        ]);
        $this->assertEquals(1, $myArray->indexOf('two'));
    }

    /**
     * Assert that BasicArray::join() returns a string as expected.
     */
    public function testJoin()
    {
        $myArray = new BasicArray([
            'one', 'two', 'three'
        ]);
        $this->assertEquals('one,two,three', $myArray->join(','));
    }

    /**
     * Assert that BasicArray::last() returns the last element in the array.
     */
    public function testLast()
    {
        $myArray = new BasicArray([
            'one', 'two', 'three'
        ]);
        $this->assertEquals('three', $myArray->last());
    }

    /**
     * Assert that BasicArray::merge() can merge another BasicArray or a primitive
     * array, by appending values to the end of the array.
     * Note: this should always be the expected behaviour unlike BasicObject::merge()
     * which can overwrite properties with associative keys of the same name.
     */
    public function testMerge()
    {
        $myArray1 = new BasicArray([
            'one', 'two', 'three'
        ]);
        $myArray2 = new BasicArray([
            'four', 'five', 'six'
        ]);
        $myArray1->merge($myArray2);
        $this->assertEquals(6, $myArray1->length());
        $this->assertEquals('one', $myArray1->first());
        $this->assertEquals('six', $myArray1->last());
    }

    /**
     * Assert that the push method appends an element to
     * the BasicArray properties.
     */
    public function testPush()
    {
        $myArray = new BasicArray();
        $this->assertEquals(0, $myArray->length());
        $myArray->push('one');
        $this->assertEquals(1, $myArray->length());
    }

    /**
     * This test simply asserts that getting a random element from
     * an array returns a valid value contained within the original array
     * but does not test for randomness.
     */
    public function testRandom()
    {
        $myArray1 = new BasicArray([
            'one', 'two', 'three'
        ]);
        $element = $myArray1->random();
        $this->assertTrue($myArray1->contains($element));
    }

    /**
     * Assert that BasicArray::reverse() swaps the order of internal elements.
     */
    public function testReverse()
    {
        $myArray1 = new BasicArray([
            'one', 'two', 'three'
        ]);
        $myArray1->reverse();
        $this->assertEquals('three', $myArray1->first());
        $this->assertEquals('one', $myArray1->last());
    }

    /**
     * Assert that BasicArray::sortAscending() orders elements as expected.
     */
    public function testSortAscending()
    {
        $myArray1 = new BasicArray([
            'one', 'two', 'three'
        ]);
        $this->assertTrue($myArray1->sortAscending());
        $this->assertEquals('one', $myArray1[0]);
        $this->assertEquals('three', $myArray1[1]);
        $this->assertEquals('two', $myArray1[2]);
    }

    /**
     * Assert that BasicArray::sortDescending() orders elements as expected.
     */
    public function testSortDescending()
    {
        $myArray1 = new BasicArray([
            'one', 'two', 'three'
        ]);
        $this->assertTrue($myArray1->sortDescending());
        $this->assertEquals('two', $myArray1[0]);
        $this->assertEquals('three', $myArray1[1]);
        $this->assertEquals('one', $myArray1[2]);
    }

    /**
     * Assert that we can sort BasicArray::sortWith() using a custom function.
     */
    public function testSortWith()
    {
        $myArray1 = new BasicArray([
            'one', 'two', 'three'
        ]);
        $this->assertTrue($myArray1->sortWith(function($a, $b) {
            return ($a > $b);
        }));
        $this->assertEquals('one', $myArray1[0]);
        $this->assertEquals('three', $myArray1[1]);
        $this->assertEquals('two', $myArray1[2]);
    }
}