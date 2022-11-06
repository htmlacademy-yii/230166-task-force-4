<?php

namespace TaskForce\Actions;

use TaskForce\Actions\AbstractAction;
use TaskForce\Models\BaseTask;
use app\models\Response;

class ActionCancel extends AbstractAction
{
    const NAME = 'cancel';
    const LABEL = 'Отказать';

    public $successCallback;

    public static function check(BaseTask $task, int $currentUserId): bool
    {
        return
            $task->getStatus() === BaseTask::STATUS_NEW
            && $currentUserId === $task->getCustomerId();
    }

    public function run(int $taskId, int $userId)
    {
        $response = Response::findOne(['task_id' => $taskId, 'user_id' => $userId]);
        $response->delete();
        $this->redirect(['/tasks', 'taskId' => $taskId]);
    }
}
