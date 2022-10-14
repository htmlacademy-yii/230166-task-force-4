<?php

namespace app\models;

use Yii;
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
            [['name'], 'string', 'max' => 32],
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

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['city_id' => 'id']);
    }



    public static function getNames(): array
    {
        return ArrayHelper::getColumn(self::find()->select('name')->asArray()->all(), 'name');
    }
}
