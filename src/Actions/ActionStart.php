<?php

namespace TaskForce\Actions;

use TaskForce\Actions\AbstractAction;
use TaskForce\Models\BaseTask;
use app\models\Response;
use app\models\Task;
use app\models\User;

class ActionStart extends AbstractAction
{
    const NAME = 'start';
    const LABEL = 'Запуск задания';

    public static function check(Task $task, User $currentUser): bool
    {
        return
            $task->status === BaseTask::STATUS_NEW
            && $currentUser->id === $task->customer_id;
    }

    public function run(int $taskId, int $userId)
    {
        $response = Response::findOne(['task_id' => $taskId, 'user_id' => $userId]);
        $response->is_approved = 1;
        $response->save(false);

        $task = Task::findOne(['id' => $taskId]);
        $task->executor_id = $userId;
        $task->status = BaseTask::STATUS_INPROGRESS;
        $task->save(false);

        $this->redirect(['/tasks', 'taskId' => $taskId]);
    }
}
