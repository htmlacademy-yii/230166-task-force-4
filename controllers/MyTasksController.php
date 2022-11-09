<?php

namespace app\controllers;

use app\models\Task;
use app\models\User;
use TaskForce\Models\BaseMyTasks;

class MyTasksController extends SecuredController
{
    /**
     * Выводит список задач для текущего пользователя
    */
    public function actionIndex($status)
    {
        $currentUser = User::getCurrentUser();
        $tasks = [];

        if ($status === BaseMyTasks::STATUS_NEW) {
            $tasks = Task::getNewTasksForCustomer($currentUser);
        }

        if ($status === BaseMyTasks::STATUS_INPROGRESS) {
            $tasks = Task::getInProgressTasks($currentUser);
        }

        if ($status === BaseMyTasks::STATUS_COMPLETE) {
            $tasks = Task::getDoneTasks($currentUser);
        }

        if ($status === BaseMyTasks::STATUS_LATE) {
            $tasks = Task::getLateTasksForExecutor($currentUser);
        }

        return $this->render('index', compact('tasks', 'status'));
    }
}

