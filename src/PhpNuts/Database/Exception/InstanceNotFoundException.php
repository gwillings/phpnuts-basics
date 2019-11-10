<?php

namespace PhpNuts\Database\Exception;

use Throwable;

/**
 * Class InstanceNotFoundException
 *
 * When a Database instance is requested, but is unrecognised.
 *
 * @package PhpNuts\Database\Exception
 */
class InstanceNotFoundException extends DatabaseException
{
    /**
     * InstanceNotFoundException constructor.
     * @param string $instanceName
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $instanceName, int $code = 0, Throwable $previous = null)
    {
        parent::__construct("Database instance with name '{$instanceName}' was not found.", $code, $previous);
    }
}
