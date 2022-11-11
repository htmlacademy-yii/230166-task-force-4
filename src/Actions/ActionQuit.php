<?php

namespace Taskforce\Actions;

use Taskforce\Actions\AbstractAction;
use Taskforce\Models\BaseTask;
use app\models\Task;
use app\models\User;
use Exception;
use Yii;
use yii\web\ServerErrorHttpException;

class ActionQuit extends AbstractAction
{
    const NAME = 'quit';
    const LABEL = 'Отказаться от задания';

    public static function check(Task $task, User $currentUser): bool
    {
        return
            $task->status === BaseTask::STATUS_INPROGRESS
            && $currentUser->id === $task->executor_id;
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
            $executor = User::findOne($executorId);
            $executor->count_failed_tasks += 1;
            $executor->rating = User::getRating($executor);
            $executor->save(false);

            $task = Task::findOne($taskId);
            $task->status = BaseTask::STATUS_FAILED;
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
