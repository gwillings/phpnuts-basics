<?php

namespace PhpNuts\Literal;

use ArrayAccess;
use Countable;

/**
 * BasicArray
 *
 * @package PhpNuts\Literal
 */
class BasicArray extends BasicObject implements Countable, ArrayAccess
{
    /**
     * Constructor can accept either a native array or a BasicArray.
     * @param array|BasicArray $properties
     */
    public function __construct($properties = [])
    {
        parent::__construct();
        foreach ($properties as $property) {
            $this->push($property);
        }
    }

    /**
     * Determine if the collection/array contains a value.
     * Returns TRUE if the value exists, else FALSE.
     *
     * @param mixed $value
     * @param bool $strict
     * @return bool
     */
    public function contains($value, bool $strict = false): bool
    {
        // there's no need to convert $value
        return in_array($value, $this->properties, $strict);
    }

    /**
     * Count elements of an object
     * @link https://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return $this->length();
    }

    /**
     * Filters elements of an array using a callback function.
     * The callback function must be able to receive $index and $value arguments.
     * If the callback function returns TRUE, the current value from array is returned into the result array.
     * Array keys are NOT preserved.
     * @param callable $callback
     * @return static
     */
    public function filter(callable $callback): BasicArray
    {
        $copy = $this->newEmptyClone();
        foreach ($this->properties as $key => $value) {
            $args = [$key, $value];
            if (call_user_func_array($callback, $args)) {
                $copy->push($value);
            }
        }
        return $copy;
    }

    /**
     * Returns the first element in the array, or NULL if there are no elements.
     * @return mixed|null
     */
    public function first()
    {
        return ($this->hasProperties()) ? reset($this->properties) : null;
    }

    /**
     * @param mixed $value
     * @param bool $strict
     * @return int|null
     */
    public function indexOf($value, bool $strict = false): ?int
    {
        $index = array_search($value, $this->properties, $strict);
        return ($index !== false) ? intval($index) : null;
    }

    /**
     * Join the array elements with a string.
     * @param string $glue
     * @return string
     */
    public function join(string $glue = ''): string
    {
        return implode($glue, $this->properties);
    }

    /**
     * Returns the last element in the array.
     * @return mixed|null
     */
    public function last()
    {
        return ($this->hasProperties()) ? end($this->properties) : null;
    }

    /**
     * @param array|BasicArray $properties
     * @return $this
     */
    public function merge($properties): BasicObject
    {
        foreach ($properties as $index => $value) {
            $this->push($value);
        }
        return $this;
    }

    /**
     * Whether a offset exists
     * @link https://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return $this->isset($offset);
    }

    /**
     * Offset to retrieve
     * @link https://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Offset to set
     * @link https://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * Offset to unset
     * @link https://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        $this->unset($offset);
    }

    /**
     * Appends an element/value to the end of the internal properties array.
     *
     * @param mixed $value
     * @return $this
     */
    public function push($value): BasicArray
    {
        $this->properties[] = $value;
        return $this;
    }

    /**
     * Returns a random element from the array
     * @return mixed
     */
    public function random()
    {
        $items = $this->getProperties();
        return $items[array_rand($items)];
    }

    /**
     * @return $this
     */
    public function reverse(): BasicArray
    {
        $this->properties = array_reverse($this->properties);
        return $this;
    }

    /**
     * @param int $sortFlags
     * @return bool
     */
    public function sortAscending(int $sortFlags = SORT_REGULAR): bool
    {
        return sort($this->properties, $sortFlags);
    }

    /**
     * @param int $sortFlags
     * @return bool
     */
    public function sortDescending(int $sortFlags = SORT_REGULAR): bool
    {
        return rsort($this->properties, $sortFlags);
    }

    /**
     * The callable function must be able to accept two parameters ($a and $b) and
     * must return an integer less than, equal to, or greater than zero if the first
     * argument is considered to be respectively less than, equal to, or greater than the second.
     * @param callable $comparisonFunction
     * @return bool
     */
    public function sortWith(callable $comparisonFunction): bool
    {
        return usort($this->properties, $comparisonFunction);
    }
}