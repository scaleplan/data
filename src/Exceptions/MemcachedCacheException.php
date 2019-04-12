<?php

namespace Scaleplan\Data\Exceptions;

/**
 * Class MemcachedCacheException
 *
 * @package Scaleplan\Data\Exceptions
 */
class MemcachedCacheException extends DataException
{
    public const MESSAGE = 'Memcached cache error.';
}
