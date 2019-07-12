<?php

namespace PhpNuts\Literal;

use InvalidArgumentException;
use Iterator;
use JsonSerializable;
use RuntimeException;
use stdClass;

class BasicObject implements Iterator, JsonSerializable
{
    /** @var array  */
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
     * We are converting getFirstName -> firstName
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
     * @param string|int $name
     * @param mixed $value
     * @return BasicObject
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
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}