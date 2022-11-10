<?php

namespace app\models\forms;

use yii\base\Model;
use app\controllers\GeoCoderController;
use app\models\User;

class SignupForm extends Model
{
    public $city;
    public $name;
    public $email;
    public $password;
    public $password_repeat;
    public $role;

    public function rules()
    {
        return [
            [['city', 'role'], 'safe'],
            [['name', 'email', 'password', 'password_repeat', 'city'], 'required'],
            [['name', 'email'], 'string', 'max' => 40],
            [['password'], 'string', 'min' => 6],
            [['password'], 'string', 'max' => 200],
            ['email', 'validateEmail'],
            [['password'], 'compare'],
            ['city', 'validateCity'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Ваше имя',
            'email' => 'Email',
            'password' => 'Пароль',
            'password_repeat' => 'Повтор пароля',
            'role' => 'Я собираюсь откликаться на заказы',
            'city' => 'Город',
        ];
    }

    public function validateEmail($attribute, $params)
    {
        if (User::findOne(['email' => $this->email])) {
            $this->addError($attribute, 'Email уже зарегистрирован');
        }
    }

    public function validateCity($attribute, $params)
    {
        $geoCoder = new GeoCoderController($this->city);
        if (!$geoCoder->getName()) {
            $this->addError($attribute, 'Город не найден');
        }
    }
}