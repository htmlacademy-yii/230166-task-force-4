<?php

class Category
{
    /**
     * Добавление
    */
    public function add() {}

    /**
     * Удаление
    */
    public function remove() {}

    /**
     * Получение категорий для одной задачи
    */
    public function getCategoriesForTask(int $taskId) {}

    /**
     * Получение всех категорий
    */
    public function getAll() {}

    /**
     * Получение категорий с которыми работает пользователь
    */
    public function getCategoriesForUser(int $userId) {}
}
