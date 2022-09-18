<?php

namespace TaskForce\Exceptions;

class NoMatchUserRoleException extends \Exception
{
    const MESSAGE = 'Пользователь не является заказчиком или исполнителем';
    const CODE = 500;

    public function __construct()
    {
        parent::__construct(self::MESSAGE, self::CODE, null);
    }
}
