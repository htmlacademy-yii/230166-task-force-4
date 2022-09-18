<?php

namespace TaskForce\Exceptions;

class NoMatchDataException extends \Exception
{
    const MESSAGE = 'Получены неверные данные';
    const CODE = 500;

    public function __construct()
    {
        parent::__construct(self::MESSAGE, self::CODE, null);
    }
}
