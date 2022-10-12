<?php

namespace app\controllers;

use yii\web\Controller;
use app\models\User;
use yii\web\NotFoundHttpException;

class UserController extends Controller
{
    public function actionIndex($id)
    {
        $user = User::findOne($id);

        if (!$user) {
            throw new NotFoundHttpException("Контакт с ID $id не найден");
        }

        return $this->render('index', compact('user'));
    }
}