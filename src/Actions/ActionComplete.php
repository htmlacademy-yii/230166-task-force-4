<?php

namespace TaskForce\Actions;

use Yii;
use TaskForce\Actions\AbstractAction;
use TaskForce\Models\BaseTask;
use app\models\Feedback;
use app\models\forms\AddFeedbackForm;

class ActionComplete extends AbstractAction
{
    const NAME = 'complete';
    const LABEL = 'Завершить задание';

    public static function check(BaseTask $task, int $currentUserId): bool
    {
        return
            $task->getStatus() === BaseTask::STATUS_INPROGRESS
            && $currentUserId === $task->getCustomerId();
    }

    public function run(int $taskId, int $userId)
    {
        $addFeedbackForm = new AddFeedbackForm();

        if (Yii::$app->request->getIsPost()) {
            if ($addFeedbackForm->load(Yii::$app->request->post())) {
                $feedback = new Feedback();
                $feedback->message = $addFeedbackForm['message'];
                $feedback->rating = 4;
                $feedback->save(false);
            }
        }

        $this->redirect(['/tasks', 'taskId' => $taskId]);
    }
}
