<?php

namespace TaskForce\TaskActions;

use TaskForce\TaskActions\AbstractAction;
use TaskForce\Models\Task;

class Respond extends AbstractAction
{
    protected $action = 'respond';
    protected $actionPresentation = 'Откликнуться на задание';

    public static function check(Task $task, int $currentUserId): bool
    {
        return
            $task->getStatus() === Task::STATUS_NEW
            && $currentUserId === $task->getExecutorId();
    }
}
