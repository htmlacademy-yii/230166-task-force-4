<?php

namespace TaskForce\Models;

class User
{

    public function __construct(private int $userId)
    {

    }

    /**
     * Метод возвращает true, если текущий пользователь является заказчиком
    */
    public function isCustomer():boolean {}

}
