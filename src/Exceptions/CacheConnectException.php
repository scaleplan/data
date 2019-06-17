<?php

namespace Scaleplan\Data\Exceptions;

/**
 * Class CacheConnectException
 *
 * @package Scaleplan\Data\Exceptions
 */
class CacheConnectException extends DataException
{
    public const MESSAGE = 'Cache connect error.';
    public const CODE = 523;
}
