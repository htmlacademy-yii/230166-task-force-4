<?php

namespace app\controllers;

use app\models\Task;

class MyTasksController extends SecuredController
{
    /**
     * Выводит список новых задач для текущего пользователя
    */
    public function actionNew()
    {
        $newTasks = Task::getNewTasksForCurrentUser();

        return $this->render('new', compact('newTasks'));
    }

    /**
     * Выводит список задач в работе
    */
    public function actionInProgress()
    {
        $inProgressTasks = Task::getProgressTasksForCurrentUser();

        return $this->render('in-progress', compact('inProgressTasks'));
    }

    /**
     * Выводит список законченнных задач
    */
    public function actionFinished()
    {
        $finishedTasks = Task::getFinishedTasksForCurrentUser();

        return $this->render('finished', compact('finishedTasks'));
    }
}

