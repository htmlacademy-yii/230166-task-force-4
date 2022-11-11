<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "file".
 *
 * @property int $id
 * @property string|null $created_at
 * @property int|null $task_id
 * @property string $url
 * @property string|null $name
 * @property int|null $size
 *
 * @property Task $task
 */
class File extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'file';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['task_id', 'size'], 'integer'],
            [['url'], 'required'],
            [['url'], 'string', 'max' => 40],
            [['name'], 'string', 'max' => 500],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::class, 'targetAttribute' => ['task_id' => 'id']],
        ];
    }

    public static function getTaskFilesAsArray($task): ?array
    {
        return self::find()->where(['task_id' => ArrayHelper::getValue($task, 'id')])->asArray()->all();
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Created At',
            'task_id' => 'Task ID',
            'url' => 'Url',
            'name' => 'Name',
            'size' => 'Size',
        ];
    }

    /**
     * Gets query for [[Task]].
     *
     * @return \yii\db\ActiveQuery|TaskQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::class, ['id' => 'task_id']);
    }
}
