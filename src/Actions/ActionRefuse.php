<?php

namespace TaskForce\Actions;

use TaskForce\Actions\AbstractAction;
use TaskForce\Models\BaseTask;
use app\models\Response;
use app\models\Task;
use app\models\User;

class ActionRefuse extends AbstractAction
{
    const NAME = 'refuse';
    const LABEL = 'Отказать';

    public $successCallback;

    public static function check(Task $task, User $currentUser): bool
    {
        return
            $task->status === BaseTask::STATUS_NEW
            && $currentUser->role === User::ROLE_EXECUTOR;
    }

    public function run(int $taskId, int $userId)
    {
        $response = Response::findOne(['task_id' => $taskId, 'user_id' => $userId]);
        $response->delete();
        header('Location: /tasks/' . $taskId);
    }
}
