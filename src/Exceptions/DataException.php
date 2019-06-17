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
    public const CODE = 400;

    /**
     * DataException constructor.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message ?: static::MESSAGE, $code ?: static::CODE, $previous);
    }
}
