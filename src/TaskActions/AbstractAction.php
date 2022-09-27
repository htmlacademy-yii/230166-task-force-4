<?php

namespace TaskForce\TaskActions;

use TaskForce\Models\Task;

abstract class AbstractAction
{
    const NAME = self::NAME;
    const EXTERNAL_NAME = self::EXTERNAL_NAME;

    /**
     * check проверяет права пользователя
     *
     * @param Task $task - объект класса Task
     * @param $currentUserId -id текущего пользователя
     *
     * @return bool
     */
    abstract public static function check(Task $task, int $currentUserId): bool;
}

