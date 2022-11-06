<?php

namespace TaskForce\Actions;

use TaskForce\Actions\AbstractAction;
use TaskForce\Models\BaseTask;
use app\models\Task;
use app\models\User;

class ActionQuit extends AbstractAction
{
    const NAME = 'quit';
    const LABEL = 'Отказаться от задания';

    public static function check(Task $task, User $currentUser): bool
    {
        return
            $task->status === BaseTask::STATUS_INPROGRESS
            && $currentUser->id === $task->executor_id;
    }

    public function run(int $taskId, int $userId)
    {
        $task = Task::find()
            ->where([
                    'task.id' => $taskId,
                    'task.executor_id' => $userId
                ])
            ->limit(1)
            ->one();

        $task->status = 'failed';
        $task->save(false);

        $this->redirect('/tasks');
    }
}
