<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use app\models\User;
use app\models\City;
use app\models\Auth;
use app\models\forms\LoginForm;
use app\models\forms\SignupForm;
use yii\web\NotFoundHttpException;
use app\models\forms\AuthClientForm;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

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

    /**
     * показываем лендинг с формой входа
     *
     * @param  ?int $authClient
     * @param  ?ing $userId
     * @return string
     */
    public function actionIndex(string $authClient = null, int $userId = null): string|Response
    {
        $this->layout = 'start';
        $loginForm = new LoginForm();
        $cities = [];
        $authClientForm = null;

        // получаем данные из формы логина
        if (Yii::$app->request->getIsPost()) {
            $loginForm->load(Yii::$app->request->post());

            if ($loginForm->validate()) {
                $user = $loginForm->getUser();

                if (Yii::$app->user->login($user)) {
                    return $this->redirect(['/tasks']);
                } else {
                    throw new NotFoundHttpException('Пользователь не залогинен!');
                }
            }
        }

        if ($authClient === 'vk' && $userId) {
            $cities = City::getAllNames();
            $authClientForm = new AuthClientForm();

            if (Yii::$app->request->getIsPost()) {//добавляем роль и город для профиля ВК
                $authClientForm->load(Yii::$app->request->post());

                if ($authClientForm->validate()) {
                    $cityName = ArrayHelper::getValue($authClientForm, 'city');
                    $user = User::findOne($userId);
                    $user->city_id = City::getCityId($cityName, $cities);
                    $user->role = ArrayHelper::getValue($authClientForm, 'role') ? User::ROLE_EXECUTOR : User::ROLE_CUSTOMER;
                    $user->save(false);

                    $this->redirect('/tasks');
                }
            }
        }

        return $this->render('index', compact('loginForm', 'authClient', 'authClientForm', 'cities'));
    }

    /**
     * actionSignup
     *
     * @return string
     */
    public function actionSignup(): string|Response
    {
        $signupForm = new SignupForm();
        $cities = City::getAllNames();

        if (Yii::$app->request->getIsPost()) {
            $signupForm->load(Yii::$app->request->post());

            if ($signupForm->validate()) {
                $user = new User;
                $cityName = (string) ArrayHelper::getValue($signupForm, 'city');
                $user->city_id = City::getCityId($cityName, $cities);
                $user->name = ArrayHelper::getValue($signupForm, 'name');
                $user->email = ArrayHelper::getValue($signupForm, 'email');
                $user->password = Yii::$app->security->generatePasswordHash($signupForm->password);
                $user->role = ArrayHelper::getValue($signupForm, 'role') ? User::ROLE_EXECUTOR : User::ROLE_CUSTOMER;

                if ($user->save(false)) {
                    $this->redirect('/');
                }
            }
        }

        return $this->render('signup', compact('signupForm', 'cities'));
    }

    /**
     * регистрация/вход через ВК
     */
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

                    $transaction = $user->getDb()->beginTransaction();

                    if ($user->validate()) {
                        $user->password = Yii::$app->security->generatePasswordHash($user->password);
                        $user->save(false);
                    }

                    $auth = new Auth([
                        'user_id' => $user->id,
                        'source' => $client->getId(),
                        'source_id' => (string)$attributes['id'],
                    ]);

                    if ($auth->save(false)) {
                        $transaction->commit();
                        Yii::$app->user->login($user);
                    } else {
                        throw new ServerErrorHttpException($auth->getErrors(), 500);
                    }

                    // открываем попап с формой для получения роли и города пользователя
                    return $this->redirect(['index', 'authClient' => 'vk', 'userId' => $user->id]);
                }
            }
        } else { // Пользователь уже зарегистрирован
            if (!$auth) { // добавляем внешний сервис аутентификации
                $auth = new Auth([
                    'user_id' => Yii::$app->user->id,
                    'source' => $client->getId(),
                    'source_id' => $attributes['id'],
                ]);
                $auth->save(false);
            }
        }

        $this->redirect('/tasks');
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