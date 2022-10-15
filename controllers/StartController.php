<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use app\models\User;
use app\models\City;
use app\models\forms\LoginForm;

class StartController extends Controller
{
    public function actionIndex()
    {
        $this->layout = 'start';

        $loginForm = new LoginForm();

        if (Yii::$app->request->getIsPost()) {
            $loginForm->load(Yii::$app->request->post());

            if ($loginForm->validate()) {
                $user = $loginForm->getUser();
                Yii::$app->user->login($user);

                return $this->goHome();
            }
        }

        return $this->render('index', compact('loginForm'));
    }


    public function actionSignup()
    {
        $user = new User();
        $cities = ArrayHelper::map(City::find()->asArray()->all(), 'id', 'name');

        if (Yii::$app->request->getIsPost()) {
            $user->load(Yii::$app->request->post());

            if ($user->validate()) {
                $user->password = Yii::$app->security->generatePasswordHash($user->password);
                $user->save(false);
                $user = [];
                $this->goBack();
            }
        }

        return $this->render('signup', compact('user', 'cities'));
    }
}