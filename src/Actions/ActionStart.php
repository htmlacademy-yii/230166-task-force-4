<?php

namespace TaskForce\Actions;

use TaskForce\Actions\AbstractAction;
use TaskForce\Models\BaseTask;
use app\models\Response;
use app\models\Task;

class ActionStart extends AbstractAction
{
    const NAME = 'start';
    const LABEL = 'Запуск задания';

    public static function check(BaseTask $task, int $currentUserId): bool
    {
        return
            $task->getStatus() === BaseTask::STATUS_NEW
            && $currentUserId === $task->getCustomerId();
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
