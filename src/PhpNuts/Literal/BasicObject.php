<?php

namespace PhpNuts\Literal;

use InvalidArgumentException;
use Iterator;
use JsonSerializable;
use RuntimeException;
use stdClass;

/**
 * BasicObject
 *
 * Basic Object is designed as a properties wrapper allowing loose typed elements
 * within its internal array. It is NOT the responsibility of the Basic Object to
 * force type. This should be the responsibility of extending classes.
 *
 * The BasicObject class is at the heart of a lot of data-based objects due to its
 * mutability and flexibility.
 *
 * Properties within a BasicObject may be added, set or removed at any time.
 * This flexibility means they are prone to mutation and non-strict types.
 * You should be aware of this when designing classes based on this type of object.
 *
 * All properties of a BasicObject are stored within an internal associative array and
 * accessing properties may be done through either:
 * - the '->' format e.g. $myObj->myProperty
 * - using a magic get or set method (our preferred method) e.g. $myObj->getMyProperty()
 * - using the built-in get() or set() methods e.g. $myObj->get('myProperty')
 *
 * I recommend using the magic get and set methods because this allow us to ease our
 * pain when refactoring code in the future.
 *
 * You can encourage auto-completion within the IDE by adding '@method' declarations
 * to your phpDoc comment blocks.
 *
 * @package PhpNuts\Literal
 */
class BasicObject extends AbstractLiteral implements Iterator, JsonSerializable
{
    /** @var mixed[] An array containing mixed type values.  */
    protected $properties = [];

    /**
     * BasicObject constructor.
     * @param array|stdClass|BasicObject $properties
     */
    public function __construct($properties = [])
    {
        $this->setProperties($properties);
    }

    /**
     * A magic method intercepts unknown method calls and mapping them to internal properties.
     * For example, we are converting getFirstName() to 'firstName' associative key.
     *
     * This object assumes that all internal property names start with a lowercase first character
     * as part of this particular object's design pattern.
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (preg_match('/^(get|set)([A-Z][\w]*)$/', $name, $matches)) {
            $action = $matches[1];
            $property = lcfirst($matches[2]);
            if (!$this->isset($property)) {
                throw new RuntimeException(__CLASS__ . "::{$name}() method does not exist");
            }
            array_unshift($arguments, $property);
            return call_user_func_array([$this, $action], $arguments);
        }
        throw new RuntimeException(__CLASS__ . "::{$name}() method does not exist");
    }

    /**
     * Debug information
     * @return array
     */
    public function __debugInfo()
    {
        return $this->getProperties();
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * @param string|int $name
     * @return bool
     */
    public function __isset($name)
    {
        return $this->isset($name);
    }

    /**
     * @param string|int $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    /**
     * @param string|int $name
     */
    public function __unset($name)
    {
        $this->unset($name);
    }

    /**
     * Return the current element
     * @link https://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return current($this->properties);
    }

    /**
     * @param array|stdClass|BasicObject $properties
     * @return $this
     */
    public function extend($properties): BasicObject
    {
        $this->properties = array_merge($this->resolveProperties($properties), $this->properties);
        return $this;
    }

    /**
     * @param string|int $name
     * @param null $default
     * @return mixed|null
     */
    public function get($name, $default = null)
    {
        return ($this->isset($name)) ? $this->properties[$name] : $default;
    }

    /**
     * @return array
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @return bool
     */
    public function hasProperties(): bool
    {
        return ($this->length() > 0);
    }

    /**
     * @param string|int $name
     * @return bool
     */
    public function isset($name): bool
    {
        return array_key_exists($name, $this->properties);
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->properties;
    }

    /**
     * Return the key of the current element
     * @link https://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return key($this->properties);
    }

    /**
     * @return array
     */
    public function keys(): array
    {
        return array_keys($this->properties);
    }

    /**
     * @return int
     */
    public function length(): int
    {
        return count($this->properties);
    }

    /**
     * @param array|stdClass|BasicObject $properties
     * @return $this
     */
    public function merge($properties): BasicObject
    {
        $this->properties = array_merge($this->properties, $this->resolveProperties($properties));
        return $this;
    }

    /**
     * @return static
     */
    public function newEmptyClone()
    {
        $newInstance = clone $this;
        $newInstance->resetProperties();
        return $newInstance;
    }

    /**
     * Move forward to next element
     * @link https://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        next($this->properties);
    }

    /**
     * @param string $oldKey The key you wish to rename
     * @param string $newKey The new name for the key
     * @return $this
     */
    public function rename($oldKey, $newKey): BasicObject
    {
        if (!$this->isset($oldKey)) {
            return $this;
        }
        $keys = $this->keys();
        $keys[array_search($oldKey, $keys)] = $newKey;
        $this->properties = array_combine($keys, $this->properties);
        return $this;
    }

    /**
     * Resets the internal properties to an empty array.
     * @return $this
     */
    public function resetProperties(): BasicObject
    {
        $this->properties = [];
        return $this;
    }

    /**
     * @param array|stdClass|BasicObject $properties
     * @return array
     */
    protected function resolveProperties($properties): array
    {
        if ($properties instanceof BasicObject) {
            return $properties->getProperties();
        }
        if ($properties instanceof stdClass) {
            return (array) $properties;
        }
        if (is_array($properties)) {
            return $properties;
        }
        throw new InvalidArgumentException('Expected an array, stdClass or BasicObject');
    }

    /**
     * Rewind the Iterator to the first element
     * @link https://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        reset($this->properties);
    }

    /**
     * @param string|int $name
     * @param mixed $value
     * @return $this
     */
    public function set($name, $value): BasicObject
    {
        $this->properties[$name] = $value;
        return $this;
    }

    /**
     * @param array|stdClass|BasicObject $properties
     * @return $this
     */
    public function setProperties($properties): BasicObject
    {
        $this->properties = $this->resolveProperties($properties);
        return $this;
    }

    /**
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param string|null $name
     * @return $this
     */
    public function unset($name): BasicObject
    {
        if ($this->isset($name)) {
            unset($this->properties[$name]);
        }
        return $this;
    }

    /**
     * Checks if current position is valid
     * @link https://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return ($this->key() !== null);
    }
}