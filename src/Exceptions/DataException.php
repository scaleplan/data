<?php

namespace Scaleplan\Data\Exceptions;

/**
 * Class DataException
 *
 * @package Scaleplan\Data\Exceptions
 */
class DataException extends \Exception
{
    public const MESSAGE = 'Data grab error.';

    /**
     * DataException constructor.
     *
     * @param string|null $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = null, int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message ?? static::MESSAGE, $code, $previous);
    }
}