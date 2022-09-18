<?php

namespace TaskForce\Exceptions;

class EmptyValueException extends \Exception
{
    const MESSAGE = 'Передано пустое значение';
    const CODE = 500;

    public function __construct()
    {
        parent::__construct(self::MESSAGE, self::CODE, null);
    }
}
