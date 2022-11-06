<?php

namespace TaskForce\Actions;

use TaskForce\Actions\AbstractAction;
use TaskForce\Models\Task;

class ActionStart extends AbstractAction
{
    const NAME = 'start';
    const LABEL = 'Запуск задания';

    public static function check(Task $task, int $currentUserId): bool
    {
        return
            $task->getStatus() === Task::STATUS_NEW
            && $currentUserId === $task->getCustomerId();
    }
}
