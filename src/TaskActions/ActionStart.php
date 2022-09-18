<?php

namespace TaskForce\TaskActions;

use TaskForce\TaskActions\AbstractAction;
use TaskForce\Models\Task;

class ActionStart extends AbstractAction
{
    protected $action = 'start';
    protected $actionPresentation = 'Запуск задания';

    public static function check(Task $task, int $currentUserId): bool
    {
        return
            $task->getStatus() === Task::STATUS_NEW
            && $currentUserId === $task->getCustomerId();
    }
}
