<?php

namespace TaskForce\Exceptions;

class NoStatusException extends \Exception
{
    const MESSAGE = 'Указан неправильный статус задачи';
    const CODE = 500;

    public function __construct()
    {
        parent::__construct(self::MESSAGE, self::CODE, null);
    }
}
