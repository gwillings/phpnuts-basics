<?php

namespace PhpNuts\File\Exception;

use Throwable;

/**
 * Class FileNotFoundException
 * @package PhpNuts\File\Exception
 */
class FileNotFoundException extends FileException
{
    /**
     * FileNotFoundException constructor.
     * @param string $filePath
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $filePath, $code = 0, Throwable $previous = null)
    {
        parent::__construct("File with path '{$filePath}' does not exist", $code, $previous);
    }
}