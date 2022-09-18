<?php

namespace TaskForce\TaskActions;

use TaskForce\TaskActions\AbstractAction;
use TaskForce\Models\Task;

class ActionComplete extends AbstractAction
{
    protected $action = 'complete';
    protected $actionPresentation = 'Завершить задание';

    public static function check(Task $task, int $currentUserId): bool
    {
        return
            $task->getStatus() === Task::STATUS_INPROGRESS
            && $currentUserId === $task->getCustomerId();
    }
}
