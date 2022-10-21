<?php

namespace app\controllers;

use Yii;
use app\models\Task;

class MyTaskController extends SecuredController
{
    public function actionNew()
    {
        $query = Task::getNewTasksQuery();
        $newTasks = $query->where(['task.id' => Yii::$app->user->getId()])->all();

        return $this->render('new', compact('newTasks'));
    }

    public function actionInProgress()
    {
        $query = Task::getNewTasksQuery();
        $inProgressTasks = $query->where(['task.id' => Yii::$app->user->getId(), 'task.status' => 'in_progress'])->all();

        return $this->render('in-progress', compact('inProgressTasks'));
    }

    public function actionFinished()
    {
        $query = Task::getNewTasksQuery();
        $finishedTasks = $query->where(['task.id' => Yii::$app->user->getId(), 'task.status' => 'done'])->all();

        return $this->render('finished', compact('finishedTasks'));
    }
}

