<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\Task;

class ProfileController extends SecuredController
{
    public function actionIndex($userId)
    {
        /**
         * Получаем пользователя из модели User по id из get-параметров
         */
        $user = User::getUserById($userId);

        /**
         * Получаем все задачи пользователя
         */
        $tasks = User::getTasks($user);
        /**
         * фильтруем задачи по статусу для получения проваленных задач
         */
        $failedTasks = array_filter($tasks, function($task) {
                return $task['status'] === 'failed';
            }
        );

        /**
         * категории пользователя
         */
        $user['categories'] = User::getCategories($user);

        if ($user['is_executor']) {
            /**
             * количество задач
            */
            $user['tasks_count']['all'] = Task::find()->where(['task.executor_id' => $user['id']])->count();
            $user['tasks_count']['failed'] = Task::find()->where(['task.executor_id' => $user['id'], 'task.status' => 'failed'])->count();

            /**
             * отзывы от заказчиков
            */
            $feedbacks = User::getFeedbacks($user);

            /**
             * место в рейтинге
            */
            $user['rate'] = User::getRate($user);
        } else {
            /**
             * количество задач
            */
            $user['tasks_count']['all'] = Task::find()->where(['task.customer_id' => $user['id']])->count();
            $user['tasks_count']['failed'] = Task::find()->where(['task.customer_id' => $user['id'], 'task.status' => 'failed'])->count();
            $feedbacks = [];
        }

        return $this->render('index', compact('user', 'feedbacks'));
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect('/');
    }
}