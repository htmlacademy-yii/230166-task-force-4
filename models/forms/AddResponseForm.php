<?php

namespace app\models\forms;

use yii\base\Model;

class AddResponseForm extends Model
{
    public $message;
    public $price;

    public function attributeLabels()
    {
        return [
            'message' => 'Ваш комментарий',
            'price' => 'Стоимость'
        ];
    }

    public function rules()
    {
        return [
            [['message', 'price'], 'safe'],
            [['message'], 'required', 'message' => 'Это обязательное поле'],
        ];
    }
}