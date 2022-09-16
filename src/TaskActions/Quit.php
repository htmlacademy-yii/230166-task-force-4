<?php

namespace TaskForce\TaskActions;

use TaskForce\TaskActions\AbstractAction;
use TaskForce\Models\Task;

class Quit extends AbstractAction
{
    protected $action = 'quit';
    protected $actionPresentation = 'Отказаться от задания';

    public static function check(Task $task, int $currentUserId): bool
    {
        return
            $task->getStatus() === Task::STATUS_INPROGRESS
            && $currentUserId === $task->getExecutorId();
    }
}
