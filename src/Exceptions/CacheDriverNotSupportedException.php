<?php

namespace Scaleplan\Data\Exceptions;

/**
 * Class CacheException
 *
 * @package Scaleplan\Data\Exceptions
 */
class CacheDriverNotSupportedException extends CacheException
{
    public const MESSAGE = 'Драйвер кэша не поддерживается.';
    public const CODE = 406;
}
