<?php

namespace app\models;

use Yii;
use Taskforce\Services\Location\GeoCoder;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "city".
 *
 * @property int $id
 * @property string|null $created_at
 * @property string $name
 * @property float|null $lat
 * @property float|null $lng
 *
 * @property Task[] $tasks
 * @property User[] $users
 */
class City extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'city';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['name'], 'required'],
            [['lat', 'lng'], 'number'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Created At',
            'name' => 'Name',
            'lat' => 'Lat',
            'lng' => 'Lng',
        ];
    }

    public static function getAllNames(): array
    {
        return ArrayHelper::getColumn(self::find()->asArray()->all(), 'name');
    }

    public static function getCityId($cityName, $cities)
    {
        if (!ArrayHelper::isIn($cityName, $cities)) {
            $geoCoder = new GeoCoder($cityName);
            $city = new City;
            $city->name = $cityName;
            $city->lat = $geoCoder->getLat();
            $city->lng = $geoCoder->getLng();
            $city->save(false);
            return $city->id;
        } else {
            return ArrayHelper::getValue(City::findOne(['name' => $cityName]), 'id');
        }
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::class, ['city_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['city_id' => 'id']);
    }
}
