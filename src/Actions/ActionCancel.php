<?php

namespace TaskForce\Actions;

use TaskForce\Actions\AbstractAction;
use TaskForce\Models\Task;

class ActionCancel extends AbstractAction
{
    const NAME = 'cancel';
    const LABEL = 'Отказать';

    public static function check(Task $task, int $currentUserId): bool
    {
        return
            $task->getStatus() === Task::STATUS_NEW
            && $currentUserId === $task->getCustomerId();
    }
}
