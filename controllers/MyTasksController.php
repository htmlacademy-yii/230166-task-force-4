<?php

namespace app\controllers;

use Yii;
use app\models\Task;

class MyTaskController extends SecuredController
{
    public function actionNew()
    {
        $query = Task::getQueryWithNewTasks();
        $newTasks = $query->where(['task.id' => Yii::$app->user->getId()])->all();

        return $this->render('new', compact('newTasks'));
    }

    public function actionInProgress()
    {
        $query = Task::getQueryWithNewTasks();
        $inProgressTasks = $query->where(['task.id' => Yii::$app->user->getId(), 'task.status' => 'in_progress'])->all();

        return $this->render('in-progress', compact('inProgressTasks'));
    }

    public function actionFinished()
    {
        $query = Task::getQueryWithNewTasks();
        $finishedTasks = $query->where(['task.id' => Yii::$app->user->getId(), 'task.status' => 'done'])->all();

        return $this->render('finished', compact('finishedTasks'));
    }
}

