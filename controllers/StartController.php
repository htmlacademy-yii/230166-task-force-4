<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use app\models\User;
use app\models\City;

class StartController extends Controller
{
    public function actionIndex()
    {
        $this->layout = 'start';

        return $this->render('index');
    }


    public function actionRegistration()
    {
        $user = new User();
        $cities = ArrayHelper::map(City::find()->asArray()->all(), 'id', 'name');

        if (Yii::$app->request->getIsPost()) {
            $user->load(Yii::$app->request->post());

            if ($user->validate()) {
                $user->password = Yii::$app->security->generatePasswordHash($user->password);
                $user->save(false);
                $user = [];
                $this->goHome();
            }
        }

        return $this->render('registration', compact('user', 'cities'));
    }
}