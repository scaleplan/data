<?php

namespace Scaleplan\Data\Exceptions;

/**
 * Class CacheConnectException
 *
 * @package Scaleplan\Data\Exceptions
 */
class CacheConnectException extends DataException
{
    public const MESSAGE = 'Ошибка подключения к кэшу.';
    public const CODE = 523;
}
