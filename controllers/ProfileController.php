<?php

namespace app\controllers;

use app\models\User;
use app\models\Task;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class ProfileController extends SecuredController
{
    public function actionIndex($executorId)
    {
        $user = User::getUserAsArray($executorId);

        if (ArrayHelper::getValue($user, 'role') === User::ROLE_CUSTOMER) {
            throw new NotFoundHttpException('Можно просматривать только профили исполнителей');
        }

        $categories = User::getCategoriesAsArray($user);

        if ($user['role'] === User::ROLE_EXECUTOR) {
            /* количество задач для исполнителя */
            $user['tasks_count']['all'] = Task::find()->where(['task.executor_id' => $user['id']])->count();
            $user['tasks_count']['failed'] = Task::find()->where(['task.executor_id' => $user['id'], 'task.status' => 'failed'])->count();

            $feedbacks = User::getFeedbacks($user);
            /* место в рейтинге */
            $user['rate'] = User::getRate($user);
        } else {
            /* количество задач для заказчика */
            $user['tasks_count']['all'] = Task::find()->where(['task.customer_id' => $user['id']])->count();
            $user['tasks_count']['failed'] = Task::find()->where(['task.customer_id' => $user['id'], 'task.status' => 'failed'])->count();
            $feedbacks = [];
        }

        return $this->render('index', compact('user', 'feedbacks', 'categories'));
    }
}