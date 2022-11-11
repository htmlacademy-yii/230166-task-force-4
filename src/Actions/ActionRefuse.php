<?php

namespace Taskforce\Actions;

use Yii;
use Taskforce\Actions\AbstractAction;
use Taskforce\Models\BaseTask;
use app\models\Response;
use app\models\Task;
use app\models\User;
use Exception;
use yii\web\ServerErrorHttpException;

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
            $response->status = Response::STATUS_REFUSE;
            $response->save(false);

            $task = Task::findOne($taskId);
            $task->status = BaseTask::STATUS_CANCELED;
            $task->save(false);

            $transaction->commit();
        } catch(ServerErrorHttpException|Exception $e) {
            $transaction->rollBack();
            throw new ServerErrorHttpException('Сервер не отвечает, попробуйте позже', 500);
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }

        header('Location: /tasks/' . $taskId);
    }
}
