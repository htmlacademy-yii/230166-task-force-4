<?php

namespace TaskForce\Actions;

use TaskForce\Actions\AbstractAction;
use TaskForce\Models\Task;

class ActionQuit extends AbstractAction
{
    const NAME = 'quit';
    const LABEL = 'Отказаться от задания';

    public static function check(Task $task, int $currentUserId): bool
    {
        return
            $task->getStatus() === Task::STATUS_INPROGRESS
            && $currentUserId === $task->getExecutorId();
    }
}
