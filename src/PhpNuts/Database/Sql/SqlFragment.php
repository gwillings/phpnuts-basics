<?php

namespace PhpNuts\Database\Sql;

use InvalidArgumentException;
use OutOfBoundsException;
use PhpNuts\Database\Sql\SqlModifier\SqlParameterModifier;

/**
 * Class SqlFragment
 *
 * A fragment of SQL code with associated parameters.
 *
 * @package PhpNuts\Database\Sql
 */
class SqlFragment
{
    /** @var string */
    private $sql;

    /** @var array */
    private $parameters;

    /**
     * SqlFragment constructor.
     * @param string $sql
     * @param array|string|int|float|bool|null $parameters A single or array of scalar parameters
     */
    public function __construct(string $sql, $parameters = [])
    {
        if (
            !is_array($parameters) &&
            (is_scalar($parameters) || is_null($parameters))
        ) {
            $parameters = [$parameters];
        } elseif (!is_array($parameters)) {
            throw new InvalidArgumentException('Parameters must be supplied as an array or single scalar value.');
        }
        $modifier = new SqlParameterModifier($sql, $parameters);
        $this->sql = $modifier->getSql();
        $this->parameters = $modifier->getParameters();
    }

    /**
     * @param int $index
     * @return string|int|float|bool|null
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
     * Returns the parameters for the SQL statement.
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * Returns the parameterised SQL statement.
     * @return string
     */
    public function getSql(): string
    {
        return $this->sql;
    }

    /**
     * NOTE: this method is MUST NOT be used directly with the Database.
     * It may only be used for debugging purposes.
     * @return string
     */
    public function toDebugString(): string
    {
        // Make a copy of the internal parameters so we don't deplete it
        $parameters = $this->parameters;
        return preg_replace_callback('/\?/', function($matches) use (&$parameters) {
            $parameter = array_shift($parameters);
            if (is_numeric($parameter) && (intval($parameter) == $parameter)) {
                return $parameter;
            } elseif (is_null($parameter)) {
                return 'NULL';
            } elseif (is_bool($parameter)) {
                return $parameter ? 'TRUE' : 'FALSE';
            }
            return sprintf("'%s'", addslashes($parameter));
        }, $this->sql);
    }
}
