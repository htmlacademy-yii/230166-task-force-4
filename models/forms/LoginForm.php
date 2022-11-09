<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\User;

class LoginForm extends Model
{
    public $email;
    public $password;

    private $_user;

    public function attributeLabels()
    {
        return [
            'email' => 'Email',
            'password' => 'Пароль'
        ];
    }

    public function rules()
    {
        return [
            [['email', 'password'], 'required', 'message' => 'Это обязательное поле'],
            [['email', 'password'], 'safe'],
            ['password', 'validatePassword']
        ];
    }

    public function getUser()
    {
        if (!$this->_user) {
            $this->_user = User::findOne(['email' => $this->email]);
        }

        return $this->_user;
    }

    public function validatePassword($attribute, $params): void
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !Yii::$app->security->validatePassword($this->password, $user->password)) {
                $this->addError($attribute, 'Неправильный email или пароль');
            }
        }
    }
}