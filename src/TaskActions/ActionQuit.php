<?php

namespace TaskForce\TaskActions;

use TaskForce\TaskActions\AbstractAction;
use TaskForce\Models\Task;

class ActionQuit extends AbstractAction
{
    const NAME = 'quit';
    const EXTERNAL_NAME = 'Отказаться от задания';

    public static function check(Task $task, int $currentUserId): bool
    {
        return
            $task->getStatus() === Task::STATUS_INPROGRESS
            && $currentUserId === $task->getExecutorId();
    }
}
