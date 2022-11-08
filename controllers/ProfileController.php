<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\Task;

class ProfileController extends SecuredController
{
    public function actionIndex($userId)
    {
        //Получаем пользователя из бд как массив
        $user = User::getUserAsArray($userId);

        //категории пользователя из бд
        $user['categories'] = User::getCategories($user);

        if ($user['role'] === User::ROLE_EXECUTOR) {
            //количество задач для исполнителя
            $user['tasks_count']['all'] = Task::find()->where(['task.executor_id' => $user['id']])->count();
            $user['tasks_count']['failed'] = Task::find()->where(['task.executor_id' => $user['id'], 'task.status' => 'failed'])->count();

            // отзывы заказчиков
            $feedbacks = User::getFeedbacks($user);

            //место в рейтинге
            $user['rate'] = User::getRate($user);
        } else {
            //количество задач для заказчика
            $user['tasks_count']['all'] = Task::find()->where(['task.customer_id' => $user['id']])->count();
            $user['tasks_count']['failed'] = Task::find()->where(['task.customer_id' => $user['id'], 'task.status' => 'failed'])->count();
            $feedbacks = [];
        }

        return $this->render('index', compact('user', 'feedbacks'));
    }

    /**
     * разлогинивание текущего пользователя
    */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect('/');
    }
}