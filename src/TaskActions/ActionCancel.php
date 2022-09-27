<?php

namespace TaskForce\TaskActions;

use TaskForce\TaskActions\AbstractAction;
use TaskForce\Models\Task;

class ActionCancel extends AbstractAction
{
    const NAME = 'cancel';
    const EXTERNAL_NAME = 'Отменить задание';

    public static function check(Task $task, int $currentUserId): bool
    {
        return
            $task->getStatus() === Task::STATUS_NEW
            && $currentUserId === $task->getCustomerId();
    }
}