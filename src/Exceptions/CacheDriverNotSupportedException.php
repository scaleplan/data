<?php

namespace Scaleplan\Data\Exceptions;

/**
 * Class CacheException
 *
 * @package Scaleplan\Data\Exceptions
 */
class CacheDriverNotSupportedException extends CacheException
{
    public const MESSAGE = 'Cache driver not supporting.';
    public const CODE = 406;
}
