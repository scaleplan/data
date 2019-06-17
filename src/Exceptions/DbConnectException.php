<?php

namespace Scaleplan\Data\Exceptions;

/**
 * Class DbConnectException
 *
 * @package Scaleplan\Data\Exceptions
 */
class DbConnectException extends DataException
{
    public const MESSAGE = 'Database connect error.';
    public const CODE = 523;
}
