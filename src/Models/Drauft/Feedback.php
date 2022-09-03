<?php

class Feedback
{
    public function __construct(
        private int $userId,
        private int $taskId
    ) {}

    /**
     * Добавление
    */
    public function add() {}

    /**
     * Удаление
    */
    public function remove() {}
}
