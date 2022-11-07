<?php

namespace TaskForce\Actions;

use Yii;
use TaskForce\Actions\AbstractAction;
use TaskForce\Models\BaseTask;
use app\models\Response;
use app\models\forms\AddResponseForm;
use app\models\Task;
use app\models\User;

class ActionRespond extends AbstractAction
{
    const NAME = 'respond';
    const LABEL = 'Откликнуться на задание';

    public static function check(Task $task, User $currentUser): bool
    {
        return
            $task->status === BaseTask::STATUS_NEW
            && $currentUser->role === User::ROLE_EXECUTOR
            && !Response::find()->where(['task_id' => $task->id, 'user_id' => $currentUser->id]);
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

        header('Location: /tasks/' . $taskId);
    }
}