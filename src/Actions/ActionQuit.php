<?php

namespace TaskForce\Actions;

use TaskForce\Actions\AbstractAction;
use TaskForce\Models\BaseTask;
use app\models\Task;

class ActionQuit extends AbstractAction
{
    const NAME = 'quit';
    const LABEL = 'Отказаться от задания';

    public static function check(BaseTask $task, int $currentUserId): bool
    {
        return
            $task->getStatus() === BaseTask::STATUS_INPROGRESS
            && $currentUserId === $task->getExecutorId();
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
