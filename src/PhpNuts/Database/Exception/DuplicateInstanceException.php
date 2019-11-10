<?php

namespace PhpNuts\Database\Exception;

use Throwable;

/**
 * Class DuplicateInstanceException
 *
 * Database instances MUST have a unique name.
 * When creating a new Database instance with a name that conflicts
 * with an existing instance, a DuplicateInstanceException is thrown.
 *
 * @package PhpNuts\Database\Exception
 */
class DuplicateInstanceException extends DatabaseException
{
    /**
     * DuplicateInstanceException constructor.
     * @param string $instanceName
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $instanceName, int $code = 0, Throwable $previous = null)
    {
        parent::__construct("A Database instance with name '{$instanceName}' already exists.", $code, $previous);
    }
}