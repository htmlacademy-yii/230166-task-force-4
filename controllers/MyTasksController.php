<?php

namespace app\controllers;

use app\models\Task;
use app\models\User;
use Taskforce\Models\BaseMyTasks;
use yii\data\Pagination;

class MyTasksController extends SecuredController
{
    /**
     * Выводит список задач для текущего пользователя
     *
     * @param  string $status
     * @return string
     */
    public function actionIndex(string $status): string
    {
        $pageSize = 4;
        $currentUser = User::getCurrentUser();
        $tasks = [];

        if ($status === BaseMyTasks::STATUS_NEW) {
            $query = Task::getNewTasksForCustomerQuery($currentUser);
        }

        if ($status === BaseMyTasks::STATUS_INPROGRESS) {
            $query = Task::getInProgressTasksQuery($currentUser);
        }

        if ($status === BaseMyTasks::STATUS_COMPLETE) {
            $query = Task::getDoneTasksQuery($currentUser);
        }

        if ($status === BaseMyTasks::STATUS_LATE) {
            $query = Task::getLateTasksForExecutorQuery($currentUser);
        }

        $pages = new Pagination([
            'totalCount' => $query->count(),
            'pageSize' => $pageSize,
            'forcePageParam' => false,
            'pageSizeParam' => false
        ]);


        $tasks = $query->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('index', compact('tasks', 'status', 'pages'));
    }
}

