<?php

namespace Taskforce\Exceptions;

class ExceptionWrongParameter extends \Exception
{
    const CODE = 500;
    public $param;
    public $func;

    public function __construct($param = null, $func = null)
    {
        $this->param = $param;
        $this->func = $func;
        parent::__construct($this->getMessageWithParam(), self::CODE, null);
    }

    public function getMessageWithParam()
    {
        $message = 'Передан некорректный параметр ' . $this->param . ' для функции ' . $this->func;

        return $message;
    }
}
