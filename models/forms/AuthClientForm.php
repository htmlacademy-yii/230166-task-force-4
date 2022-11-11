<?php

namespace app\models\forms;

use yii\base\Model;
use TaskForce\Services\Location\GeoCoder;

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

    /**
     * validateCity
    */
    public function validateCity($attribute, $params)
    {
        $geoCoder = new GeoCoder($this->city);
        if (!$geoCoder->getName()) {
            $this->addError($attribute, 'Город не найден');
        }
    }
}