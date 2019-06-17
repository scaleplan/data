<?php

namespace Scaleplan\Data\Exceptions;

/**
 * Class RedisOperationException
 *
 * @package Scaleplan\Data\Exceptions
 */
class RedisOperationException extends RedisCacheException
{
    public const MESSAGE = 'Операция с Redis не удалась.';
    public const CODE = 500;
}
