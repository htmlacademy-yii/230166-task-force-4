<?php

namespace TaskForce\Actions;

use Yii;
use TaskForce\Actions\AbstractAction;
use TaskForce\Models\BaseTask;
use app\models\Feedback;
use app\models\forms\AddFeedbackForm;
use app\models\Task;
use app\models\User;

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
                    $executor->save();

                    $task = Task::findOne($taskId);
                    $task->status = BaseTask::STATUS_COMPLETE;
                    $task->save(false);

                    $transaction->commit();
                } catch(\Exception $e) {
                    $transaction->rollBack();
                    throw $e;
                } catch (\Throwable $e) {
                    $transaction->rollBack();
                    throw $e;
                }
            }
        }

        header('Location: /tasks/' . $taskId);
    }
}
