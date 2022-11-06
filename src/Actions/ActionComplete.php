<?php

namespace TaskForce\Actions;

use TaskForce\Actions\AbstractAction;
use TaskForce\Models\Task;

class ActionComplete extends AbstractAction
{
    const NAME = 'complete';
    const LABEL = 'Завершить задание';

    public static function check(Task $task, int $currentUserId): bool
    {
        return
            $task->getStatus() === Task::STATUS_INPROGRESS
            && $currentUserId === $task->getCustomerId();
    }
}
