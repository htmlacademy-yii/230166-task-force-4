<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use app\models\User;
use app\models\City;
use app\models\Auth;
use app\models\forms\LoginForm;

class StartController extends Controller
{
    public function actions()
    {
        return [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->layout = 'start';
        $loginForm = new LoginForm();

        if (Yii::$app->request->getIsPost()) {
            $loginForm->load(Yii::$app->request->post());

            if ($loginForm->validate()) {
                $user = $loginForm->getUser();
                Yii::$app->user->login($user);

                return $this->redirect(['/profile', 'userId' => $user['id']]);
            }
        }

        return $this->render('index', compact('loginForm'));
    }

    public function actionSignup()
    {
        $user = new User();
        $city = new City();
        $cities = ArrayHelper::getColumn(City::find()->asArray()->all(), 'name');

        if (Yii::$app->request->getIsPost()) {
            $user->load(Yii::$app->request->post());

            if ($user->validate()) {
                $user->password = Yii::$app->security->generatePasswordHash($user->password);
                $user->role = $user['role'] ? User::ROLE_EXECUTOR : User::ROLE_CUSTOMER;
                $user->city_id = City::find()->select('id')->where(['name' => $user['city']]);

                $user->save(false);
                $this->redirect('/');
            }
        }

        return $this->render('signup', compact('user', 'city', 'cities'));
    }

    public function onAuthSuccess($client)
    {
        $attributes = $client->getUserAttributes();

        /* @var $auth Auth */
        $auth = Auth::find()->where([
            'source' => $client->getId(),
            'source_id' => $attributes['id'],
        ])->one();

        if (Yii::$app->user->isGuest) {
            if ($auth) { // авторизация
                $user = $auth->user;
                Yii::$app->user->login($user);
            } else { // регистрация
                if (isset($attributes['email']) && User::find()->where(['email' => $attributes['email']])->exists()) {
                    Yii::$app->getSession()->setFlash('error', [
                        Yii::t('app', "Пользователь с такой электронной почтой как в {client} уже существует, но с ним не связан. Для начала войдите на сайт использую электронную почту, для того, что бы связать её.", ['client' => $client->getTitle()]),
                    ]);
                } else {
                    $password = Yii::$app->security->generateRandomString(6);

                    $user = new User;
                    $user->name = $attributes['first_name'] . ' ' . $attributes['last_name'];
                    $user->email = $attributes['email'];
                    $user->date_of_birth = date('Y-m-d', Yii::$app->formatter->asTimestamp($attributes['bdate']));
                    $user->avatar = $attributes['photo'];
                    $user->password = $password;
                    $user->password_repeat = $password;

                    if ($user->validate()) {
                        $user->password = Yii::$app->security->generatePasswordHash($user->password);
                        $user->save(false);
                    }

                    $transaction = $user->getDb()->beginTransaction();

                    $auth = new Auth([
                        'user_id' => $user->id,
                        'source' => $client->getId(),
                        'source_id' => (string)$attributes['id'],
                    ]);

                    if ($auth->save()) {
                        $transaction->commit();
                        Yii::$app->user->login($user);
                    } else {
                        print_r($auth->getErrors());
                    }
                }
            }
        } else { // Пользователь уже зарегистрирован
            if (!$auth) { // добавляем внешний сервис аутентификации
                $auth = new Auth([
                    'user_id' => Yii::$app->user->id,
                    'source' => $client->getId(),
                    'source_id' => $attributes['id'],
                ]);
                $auth->save();
            }
        }

        $this->redirect('/tasks');
    }
}