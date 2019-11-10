<?php

namespace PhpNuts\Literal;

use JsonSerializable;

/**
 * Class AbstractValue
 *
 * This class is generally used to store a single value for comparison.
 * Most of the time it's used when comparing values from an ENUM column in the database.
 * For example, a status column with a number of options.
 *
 * @package PhpNuts\Literal
 */
abstract class AbstractValue implements JsonSerializable
{
    /** @var string */
    private $value;

    /**
     * AbstractValue constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getValue();
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return bool
     */
    public function is(string $value): bool
    {
        return (strtolower($value) === strtolower($this->value));
    }

    /**
     * @param string[] $values
     * @return bool
     */
    public function isWithin(array $values): bool
    {
        foreach ($values as $value) {
            if ($this->is($value)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return string
     */
    public function jsonSerialize()
    {
        return $this->getValue();
    }

    /**
     * @param string $value
     * @return static
     */
    public static function newInstance(string $value): AbstractValue
    {
        return new static($value);
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->getValue();
    }
}