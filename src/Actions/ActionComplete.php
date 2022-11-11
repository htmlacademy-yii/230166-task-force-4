<?php

namespace Taskforce\Actions;

use Yii;
use Taskforce\Actions\AbstractAction;
use Taskforce\Models\BaseTask;
use app\models\Feedback;
use app\models\forms\AddFeedbackForm;
use app\models\Task;
use app\models\User;
use Exception;
use yii\web\ServerErrorHttpException;

class ActionComplete extends AbstractAction
{
    const NAME = 'complete';
    const LABEL = 'Завершить задание';

    public static function check(Task $task, User $currentUser): bool
    {
        return
            $task->status === BaseTask::STATUS_INPROGRESS
            && $currentUser->id === $task->customer_id;
    }

    /**
     * run
     *
     * @param  int $taskId
     * @param  int $customerId
     * @param  int $executorId
     * @throws ServerErrorHttpException
     */
    public function run(int $taskId, int $customerId, int $executorId)
    {
        $addFeedbackForm = new AddFeedbackForm();

        if (Yii::$app->request->getIsPost()) {
            $addFeedbackForm->load(Yii::$app->request->post());

            if ($addFeedbackForm->validate()) {
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    $feedback = new Feedback([
                        'message' => $addFeedbackForm['message'],
                        'rating' => (int) $addFeedbackForm['rating'],
                        'task_id' => $taskId,
                        'customer_id' => $customerId,
                        'executor_id' => $executorId,
                    ]);

                    $feedback->save(false);

                    $executor = User::findOne($executorId);
                    $executor->count_feedbacks += 1;
                    $executor->rating = User::getRating($executor);
                    $executor->save(false);


                    $task = Task::findOne($taskId);
                    $task->status = BaseTask::STATUS_COMPLETE;
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
    }
}
