<?php

namespace TaskForce\Exceptions;

class ExceptionRequestValueIsEmpty extends \Exception
{
    const MESSAGE = 'Запрашиваемое значение отсутствует';
    const CODE = 500;

    public function __construct()
    {
        parent::__construct(self::MESSAGE, self::CODE, null);
    }
}
