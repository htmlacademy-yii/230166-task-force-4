<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "response".
 *
 * @property int $id
 * @property string|null $created_at
 * @property int $task_id
 * @property int $executor_id
 * @property string|null $status
 * @property string $message
 * @property int|null $price
 *
 * @property User $executor
 * @property Task $task
 */
class Response extends \yii\db\ActiveRecord
{
    const STATUS_NEW = 'new';
    const STATUS_REFUSE = 'refuse';
    const STATUS_APROVE = 'aprove';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'response';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['task_id', 'executor_id', 'message'], 'required'],
            [['task_id', 'executor_id', 'price'], 'integer'],
            [['status'], 'string'],
            [['message'], 'string', 'max' => 500],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::class, 'targetAttribute' => ['task_id' => 'id']],
            [['executor_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['executor_id' => 'id']],
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
            'task_id' => 'Task ID',
            'executor_id' => 'Executor ID',
            'status' => 'Status',
            'message' => 'Message',
            'price' => 'Price',
        ];
    }

    /**
     * Gets query for [[Executor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExecutor()
    {
        return $this->hasOne(User::class, ['id' => 'executor_id']);
    }

    /**
     * Gets query for [[Task]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::class, ['id' => 'task_id']);
    }
}
