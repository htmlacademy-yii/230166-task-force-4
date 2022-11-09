<?php

namespace app\models\forms;

use yii\base\Model;

class AddFeedbackForm extends Model
{
    public $message;
    public $rating;

    public function attributeLabels()
    {
        return [
            'message' => 'Ваш комментарий',
            'rating' => 'Оценка работы'
        ];
    }

    public function rules()
    {
        return [
            [['message', 'rating'], 'safe'],
            [['message'], 'required', 'message' => 'Это обязательное поле'],
        ];
    }
}