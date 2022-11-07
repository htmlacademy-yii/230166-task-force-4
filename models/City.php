<?php

namespace app\models;

use app\controllers\GeoCoderController;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "city".
 *
 * @property int $id
 * @property string|null $created_at
 * @property string $name
 * @property string $address
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
            [['name'], 'string', 'max' => 100],
            ['name', 'validateName'],
            [['address'], 'string', 'max' => 500],
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
            'name' => 'Город',
            'address' => 'Address',
            'lat' => 'Lat',
            'lng' => 'Lng',
        ];
    }

    public function validateName($attribute, $params): void
    {
        if (!$this->hasErrors()) {
            $geoCoder = new GeoCoderController($this->name);

            if (!ArrayHelper::getValue($geoCoder->data, 'GeoObjectCollection.featureMember')) {
                $this->addError($attribute, 'Этот адрес не верен');
            }

            if (ArrayHelper::getValue($geoCoder->data, 'GeoObjectCollection.metaDataProperty.GeocoderResponseMetaData.found') > 1) {
                $this->addError($attribute, 'Есть несколько совпадений, введите более точный адрес');
            }
        }
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
