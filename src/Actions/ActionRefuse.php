<?php

namespace TaskForce\Actions;

use app\models\Feedback;
use Yii;
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
            && $task->customer_id === $currentUser->id;
    }

    public function run(int $taskId, int $executorId)
    {
        $response = Response::findOne(['task_id' => $taskId, 'executor_id' => $executorId]);
        $response->status = Response::STATUS_REFUSE;
        $response->save(false);

        header('Location: /tasks/' . $taskId);
    }
}
