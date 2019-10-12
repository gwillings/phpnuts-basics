<?php

namespace PhpNuts\File\Exception;

use Throwable;

/**
 * Class ParseIniFileException
 * @package PhpNuts\File\Exception
 */
class ParseIniFileException extends FileException
{
    /**
     * ParseIniFileException constructor.
     * @param string $filePath
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $filePath, $code = 0, Throwable $previous = null)
    {
        parent::__construct("Unable to parse INI file '{$filePath}'.", $code, $previous);
    }
}