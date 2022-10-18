<?php

namespace app\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use app\models\User;

class ProfileController extends SecuredController
{
    public function actionIndex()
    {
        if ($id = Yii::$app->user->getId()) {
            $user = User::findOne($id);
        }

        if (!$user) {
            throw new NotFoundHttpException("Контакт с ID $id не найден");
        }

        return $this->render('index', compact('user'));
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSettings()
    {
        return $this->render('settings');
    }

    public function actionTasks()
    {
        return $this->render('tasks');
    }

}