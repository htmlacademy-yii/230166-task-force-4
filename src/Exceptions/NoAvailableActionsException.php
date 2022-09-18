<?php

namespace TaskForce\Exceptions;

class NoAvailableActionsException extends \Exception
{
    const MESSAGE = 'Нет доступных действий';
    const CODE = 500;

    public function __construct()
    {
        parent::__construct(self::MESSAGE, self::CODE, null);
    }
}
