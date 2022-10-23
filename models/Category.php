<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string|null $created_at
 * @property string $name
 * @property string|null $label
 *
 * @property Task[] $tasks
 * @property UserCategory[] $userCategories
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['name'], 'required'],
            [['name', 'label'], 'string', 'max' => 40],
            [['name'], 'unique'],
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
            'label' => 'Label',
        ];
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery|TaskQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::class, ['category_id' => 'id']);
    }

    /**
     * Gets query for [[UserCategories]].
     *
     * @return \yii\db\ActiveQuery|UserCategoryQuery
     */
    public function getUserCategories()
    {
        return $this->hasMany(UserCategory::class, ['category_id' => 'id']);
    }

    public static function getUserCategoriesById($userId)
    {
        return Category::find()
        ->join('INNER JOIN', 'user_category', 'user_category.category_id = category.id')
        ->join('INNER JOIN', 'user', 'user_category.user_id = user.id')
        ->where(['user.id' => $userId])
        ->asArray()
        ->all();
    }

    public static function getMapIdsToLabels()
    {
        return ArrayHelper::map(self::find()->asArray()->all(), 'id', 'label');
    }

    public static function getUserCategoriesIds($userId): ?array
    {
        $userCategories = self::getUserCategoriesById($userId);

        if ($userCategories) {
            return ArrayHelper::getColumn($userCategories, 'id');
        }

        return null;
    }
}
