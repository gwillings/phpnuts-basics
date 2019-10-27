<?php

namespace PhpNuts\Database\Sql\SqlModifier;

/**
 * Class SqlParameterModifier
 *
 * Checks the SQL statement and parameters for special cases where we allow
 * arrays and named parameters and turns them into expected ? parameters.
 *
 * @package PhpNuts\Database\Sql\SqlModifier
 */
class SqlParameterModifier
{
    /** @var string */
    private $sql;

    /** @var array */
    private $namedParameters;

    /** @var array */
    private $parameters;

    /** @var array */
    private $queryParameters;

    /**
     * SqlParameterModifier constructor.
     * @param string $sql
     * @param array $parameters
     */
    public function __construct(string $sql, array $parameters = [])
    {
        $this->modifySql($sql, $parameters);
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @return string
     */
    public function getSql(): string
    {
        return $this->sql;
    }

    /**
     * Determines whether $value is an integer...ish.
     * @param $value
     * @return bool
     */
    private function isIntegerish($value): bool
    {
        return (is_numeric($value) && (intval($value) == $value));
    }

    /**
     * @param string $sql
     * @param array $parameters
     * @return $this
     */
    private function modifySql(string $sql, array $parameters): SqlParameterModifier
    {
        $this->splitParameters($parameters);
        /**
         * This regex looks pretty scary, but it matches either an anonymous parameter ?, or an named parameter :name
         * or a string encapsulated in single or double quotes (so that we can ignore strings).
         */
        $this->sql = preg_replace_callback('/(\?|:[a-z0-9\-_]+?\b|(?:\\\\"|"(?:\\\\"|[^"])*"|(\+))|(?:\\\\\'|\'(?:\\\\\'|[^\'])*\'|(\+)))/i', function($matches) {
            $match = $matches[0];
            if (in_array(substr($match, 0, 1), ["'", '"', '+'])) {
                return $match;
            }
            if ($match === '?') {
                $parameter = array_shift($this->queryParameters);
            } else {
                $name = ltrim($match, ':');
                $parameter = $this->namedParameters[$name];
            }
            if (!is_array($parameter)) {
                $this->parameters[] = $parameter;
                return '?';
            }
            $qs = implode(', ', array_fill(0, count($parameter), '?'));
            array_splice($this->parameters, count($this->parameters), 0, $parameter);
            return "({$qs})";
        }, $sql);
        return $this;
    }

    /**
     * @param array $parameters
     * @return $this
     */
    private function splitParameters(array $parameters): SqlParameterModifier
    {
        $this->parameters = [];
        $this->queryParameters = [];
        $this->namedParameters = [];
        foreach ($parameters as $index => $parameter) {
            if ($this->isIntegerish($index)) {
                $this->queryParameters[] = $parameter;
            } else {
                $name = ltrim($index, ':');
                $this->namedParameters[$name] = $parameter;
            }
        }
        return $this;
    }
}