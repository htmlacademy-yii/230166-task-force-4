<?php

namespace app\models;

/**
 * This is the model class for table "location".
 *
 * @property int $id
 * @property string|null $created_at
 * @property string $address
 * @property string $city
 * @property float|null $lat
 * @property float|null $lng
 */
class Location extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
    */
    public static function tableName()
    {
        return 'location';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['address'], 'required'],
            [['lat', 'lng'], 'number'],
            [['city'], 'string', 'max' => 100],
            [['address'], 'string', 'max' => 500],
            [['city'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city' => 'name']]
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
            'address' => 'Адрес',
            'city' => 'City',
            'lat' => 'Lat',
            'lng' => 'Lng',
        ];
    }
}
