<?php

namespace PhpNuts\Database\Sql;

use PDO;

/**
 * Class SqlParam
 * @package PhpNuts\Database\Sql
 */
class SqlParam
{
    /**
     * Escape SQL wildcards which could skew SQL search results.
     *
     * When using a LIKE comparison in SQL to obtain a case-insensitive match,
     * you should escape SQL wildcards to avoid inaccurate matches.
     *
     * @param string $value
     * @return string
     */
    public static function escapeLikeWildcards(string $value)
    {
        return preg_replace('/(%|_)/', "\\\\$1", $value);
    }

    /**
     * Returns the PDO Parameter type for the value supplied.
     * This is used when binding values to a prepared statement.
     * @param mixed $value
     * @return int
     */
    public static function getPdoType($value)
    {
        if (is_null($value)) {
            return PDO::PARAM_NULL;
        }
        if (is_bool($value)) {
            return PDO::PARAM_BOOL;
        }
        if (is_int($value) || is_float($value)) {
            return PDO::PARAM_INT;
        }
        return PDO::PARAM_STR;
    }
}