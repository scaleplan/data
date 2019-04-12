<?php

namespace Scaleplan\Data\Exceptions;

/**
 * Class RedisCacheException
 *
 * @package Scaleplan\Data\Exceptions
 */
class RedisCacheException extends DataException
{
    public const MESSAGE = 'Redis cache error.';
}
