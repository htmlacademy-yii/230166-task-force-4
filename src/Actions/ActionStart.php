<?php

namespace Taskforce\Actions;

use Yii;
use Taskforce\Actions\AbstractAction;
use Taskforce\Models\BaseTask;
use app\models\Response;
use app\models\Task;
use app\models\User;
use yii\web\ServerErrorHttpException;

class ActionStart extends AbstractAction
{
    const NAME = 'start';
    const LABEL = 'Принять';

    public static function check(Task $task, User $currentUser): bool
    {
        return
            $task->status === BaseTask::STATUS_NEW
            && $currentUser->id === $task->customer_id;
    }

    /**
     * run
     *
     * @param  int $taskId
     * @param  int $executorId
     * @throws ServerErrorHttpException
     */
    public function run(int $taskId, int $executorId)
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $response = Response::findOne(['task_id' => $taskId, 'executor_id' => $executorId]);
            $response->status = Response::STATUS_APROVE;
            $response->save(false);

            $task = Task::findOne(['id' => $taskId]);
            $task->executor_id = $executorId;
            $task->status = BaseTask::STATUS_INPROGRESS;
            $task->save(false);

            $transaction->commit();
        } catch(ServerErrorHttpException $e) {
            $transaction->rollBack();
            throw new ServerErrorHttpException('Сервер не отвечает, попробуйте позже', 500);
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }

        header('Location: /tasks/' . $taskId);
    }
}
