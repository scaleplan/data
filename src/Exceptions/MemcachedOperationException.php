<?php

namespace Scaleplan\Data\Exceptions;

/**
 * Class MemcachedOperationException
 *
 * @package Scaleplan\Data\Exceptions
 */
class MemcachedOperationException extends RedisCacheException
{
    public const MESSAGE = 'Операция с Memcached не удалась.';
    public const CODE = 500;
}
