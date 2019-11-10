<?php

namespace PhpNuts\Database\Exception;

use Throwable;

/**
 * Class DuplicateInstanceException
 *
 * Database instances MUST have a unique reference.
 * When creating a new Database instance with a reference that conflicts
 * with an existing instance, a DuplicateInstanceException is thrown.
 *
 * @package PhpNuts\Database\Exception
 */
class DuplicateInstanceException extends DatabaseException
{
    /**
     * DuplicateInstanceException constructor.
     * @param string $reference The database instance reference.
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $reference, int $code = 0, Throwable $previous = null)
    {
        parent::__construct("A Database instance with reference '{$reference}' already exists.", $code, $previous);
    }
}