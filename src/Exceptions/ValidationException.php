<?php

namespace Scaleplan\Data\Exceptions;

/**
 * Class ValidationException
 *
 * @package Scaleplan\Data\Exceptions
 */
class ValidationException extends DataException
{
    public const MESSAGE = 'Ошибка валидации.';
    public const CODE = 422;
}
