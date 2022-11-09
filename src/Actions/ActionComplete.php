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

            if ($addFeedbackForm->validate) {
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    $feedback = new Feedback();
                    $feedback->message = $addFeedbackForm['message'];
                    $feedback->rating = (int) $addFeedbackForm['rating'];
                    $feedback->customerId = $customerId;
                    $feedback->executorId = $executorId;
                    $feedback->save(false);

                    $executor = User::findOne($executorId);
                    $executor->count_feedbacks += 1;
                    $executor->rating = Feedback::find()->where(['executor_id' => $executorId])->sum('rating') / $executor->count_feedbacks + $executor->count_failed_tasks;
                    $executor->save();

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
