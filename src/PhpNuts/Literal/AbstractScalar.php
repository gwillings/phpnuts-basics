<?php

namespace PhpNuts\Literal;

use JsonSerializable;

/**
 * Class AbstractScalar
 * @package PhpNuts\Literal
 */
abstract class AbstractScalar extends AbstractLiteral implements JsonSerializable
{
    /** @var string|int|float|bool */
    protected $value;

    /**
     * AbstractScalar constructor.
     * @param $value
     */
    public function __construct($value)
    {
        $this->setValue($value);
    }

    /**
     * Returns the native scalar value
     * @return string|int|float|bool
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function isScalar(): bool
    {
        return true;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->getValue();
    }

    /**
     * Set the internal native value.
     * @param $value
     * @return $this
     */
    public function setValue($value): AbstractLiteral
    {
        $this->value = $value;
        return $this;
    }
}