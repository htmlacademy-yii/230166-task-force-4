<?php

namespace app\models\forms;

use yii\base\Model;
use app\controllers\GeoCoderController;

class AuthClientForm extends Model
{
    public $role;
    public $city;

    public function rules()
    {
        return [
            [['role', 'city'], 'safe'],
            ['city', 'required'],
            ['city', 'validateCity'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'city' => 'Город',
            'role' => 'Я собираюсь откликаться на заказы',
        ];
    }

    public function validateCity($attribute, $params)
    {
        $geoCoder = new GeoCoderController($this->city);
        if (!$geoCoder->getName()) {
            $this->addError($attribute, 'Город не найден');
        }
    }
}