<?php

namespace Taskforce\Actions;

use Yii;
use Taskforce\Actions\AbstractAction;
use Taskforce\Models\BaseTask;
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
            && !Response::find()->where(['task_id' => $task->id, 'executor_id' => $currentUser->id])->limit(1)->one();
    }

    /**
     * run
     *
     * @param int $taskId
     * @param int $executorId
     */
    public function run(int $taskId, int $executorId)
    {
        $addResponseForm = new AddResponseForm();

        if (Yii::$app->request->getIsPost()) {
            $addResponseForm->load(Yii::$app->request->post());

            if ($addResponseForm->validate()) {
                $response = new Response();
                $response->task_id = $taskId;
                $response->executor_id = $executorId;
                $response->message = $addResponseForm['message'];
                $response->price = $addResponseForm['price'];
                $response->save(false);

                header('Location: /tasks/' . $taskId);
            }
        }
    }
}
