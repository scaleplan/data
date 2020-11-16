<?php

namespace Scaleplan\Data\Exceptions;

/**
 * Class DbConnectException
 *
 * @package Scaleplan\Data\Exceptions
 */
class DbConnectException extends DataException
{
    public const MESSAGE = 'Ошибка подключения к базе данных.';
    public const CODE = 523;
}
