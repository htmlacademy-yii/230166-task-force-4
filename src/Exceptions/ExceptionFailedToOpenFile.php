<?php

namespace TaskForce\Exceptions;

class ExceptionFailedToOpenFile extends \Exception
{
    const CODE = 500;
    public string $file;

    public function __construct(string $file = null)
    {
        $this->file = $file;
        parent::__construct($this->getMessageFiledToOpenFile(), self::CODE, null);
    }

    public function getMessageFiledToOpenFile(): string
    {
        $message = 'Не удалось открыть файл ' . $this->file . ' на чтение';

        return $message;
    }
}
