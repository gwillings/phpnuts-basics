<?php

namespace PhpNuts\Database\Sql;

use OutOfBoundsException;

/**
 * Class SqlBlock
 *
 * A SQL Block may represent one or more SQL Statements or even SQL fragments.
 * A SQL Block does not care about parsing or SQL validation because this makes it
 * versatile for a number of applications.
 *
 * @package PhpNuts\Database\Sql
 */
class SqlBlock
{
    /** @var SqlFragment[] */
    private $fragments;

    /** @var string */
    private $separator;

    /**
     * SqlBlock constructor.
     * @param string $separator
     */
    public function __construct(string $separator = "\n")
    {
        $this->separator = $separator;
        $this->fragments = [];
    }

    /**
     * @param SqlFragment $fragment
     * @return $this
     */
    public function addFragment(SqlFragment $fragment): SqlBlock
    {
        $this->fragments[] = $fragment;
        return $this;
    }

    /**
     * @return SqlFragment[]
     */
    public function getFragments(): array
    {
        return $this->fragments;
    }

    /**
     * @param int $index
     * @return mixed
     */
    public function getParameterAt(int $index)
    {
        $params = $this->getParameters();
        if (!array_key_exists($index, $params)) {
            throw new OutOfBoundsException("Parameter index {$index} not found");
        }
        return $params[$index];
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        $parameters = [];
        foreach ($this->fragments as $statement) {
            $parameters = array_merge($parameters, $statement->getParameters());
        }
        return $parameters;
    }

    /**
     * @return string
     */
    public function getSeparator(): string
    {
        return $this->separator;
    }

    /**
     * @return string
     */
    public function getSql(): string
    {
        $sql = [];
        foreach ($this->fragments as $statement) {
            $sql[] = $statement->getSql();
        }
        return implode($this->getSeparator(), $sql);
    }

    /**
     * @return bool
     */
    public function hasFragments(): bool
    {
        return ($this->length() > 0);
    }

    /**
     * @return int
     */
    public function length(): int
    {
        return count($this->fragments);
    }

    /**
     * @return string
     */
    public function toDebugString(): string
    {
        $fragment = new SqlFragment($this->getSql(), $this->getParameters());
        return $fragment->toDebugString();
    }
}