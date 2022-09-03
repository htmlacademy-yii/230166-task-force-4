<?php

class Response
{
    public function __construct(
        private int $userId
    ) {}

    /**
     * Добавление
    */
    public function add() {}

    /**
     * Удаление
    */
    public function remove() {}

    /**
     * Получение всех откликов пользователя
    */
    public function getAll() {}
}
