<?php

namespace TaskForce\TaskActions;

use TaskForce\Models\Task;

abstract class AbstractAction
{
    protected $action;
    protected $actionPresentation;

    /**
     * возвращает внутреннее название действия
     *
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     *возвращает название действия
     *
     * @return string
     */
    public function getActionPresentation(): string
    {
        return $this->actionPresentation;
    }

    /**
     * проверяет права пользователя
     *
     * @param Task $task - объект класса Task
     * @param $currentUserId -id текущего пользователя
     *
     * @return bool
     */
    abstract public static function check(Task $task, int $currentUserId): bool;
}

