<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\User;

class SecurityForm extends Model
{
    public $currentPassword;
    public $newPassword;
    public $repeatPassword;

    public function rules()
    {
        return [
            [['currentPassword', 'newPassword', 'repeatPassword'], 'required', 'message' => 'Это обязательное поле'],
            [['repeatPassword'], 'compare', 'compareAttribute' => 'newPassword'],
            [['newPassword'], 'string', 'min' => 6],
            [['newPassword'], 'string', 'max' => 64],
            ['currentPassword', 'validatePassword']
        ];
    }

    public function attributeLabels()
    {
        return [
            'currentPassword' => 'Текущий пароль',
            'newPassword' => 'Новый пароль',
            'repeatPassword' => 'Повторите пароль',
        ];
    }

    public function validatePassword($attribute, $params): void
    {
        if (!$this->hasErrors()) {
            $user = User::findOne(User::getCurrentUserId());

            if (!$user || !Yii::$app->security->validatePassword($this->currentPassword, $user->password)) {
                $this->addError($attribute, 'Неверный пароль');
            }
        }
    }
}