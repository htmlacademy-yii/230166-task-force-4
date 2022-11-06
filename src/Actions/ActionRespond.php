<?php

namespace TaskForce\Actions;

use Yii;
use TaskForce\Actions\AbstractAction;
use TaskForce\Models\BaseTask;
use app\models\Response;
use app\models\Task;
use app\models\forms\AddResponseForm;

class ActionRespond extends AbstractAction
{
    const NAME = 'respond';
    const LABEL = 'Откликнуться на задание';

    public static function check(BaseTask $task, int $currentUserId): bool
    {
        return
            $task->getStatus() === BaseTask::STATUS_NEW
            && $currentUserId === $task->getExecutorId();
    }

    public function run(int $taskId, int $userId)
    {
        $addResponseForm = new AddResponseForm();

        if (Yii::$app->request->getIsPost()) {
            $addResponseForm->load(Yii::$app->request->post());

            if ($addResponseForm->validate()) {
                $response = new Response();
                $response->task_id = $taskId;
                $response->user_id = $userId;
                $response->message = $addResponseForm['message'];
                $response->price = $addResponseForm['price'];
                $response->save(false);
            }
        }

        $this->redirect(['/tasks', 'taskId' => $taskId]);
    }
}
