<?php

namespace TaskForce\Exceptions;

class NoMatchCustomerException extends \Exception
{
    const MESSAGE = 'Нет доступных действий';
    const CODE = 500;

    public function __construct()
    {
        parent::__construct(self::MESSAGE, self::CODE, null);
    }
}
