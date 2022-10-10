<?php

namespace app\models;

use Yii;
use TaskForce\Models\Task as BaseTask;

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property string|null $created_at
 * @property int|null $customer_id
 * @property int|null $executor_id
 * @property int|null $category_id
 * @property string|null $status
 * @property string $title
 * @property string $text
 * @property float|null $price
 * @property string|null $deadline
 *
 * @property Category $category
 * @property User $customer
 * @property User $executor
 * @property Feedback[] $feedbacks
 * @property File[] $files
 * @property Response[] $responses
 */
class Task extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'deadline'], 'safe'],
            [['customer_id', 'executor_id', 'category_id'], 'integer'],
            [['status'], 'string'],
            [['title', 'text'], 'required'],
            [['price', 'id'], 'number'],
            [['title'], 'string', 'max' => 500],
            [['text'], 'string', 'max' => 1000],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['customer_id' => 'id']],
            [['executor_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['executor_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
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
            'customer_id' => 'Customer ID',
            'executor_id' => 'Executor ID',
            'category_id' => 'Category ID',
            'status' => 'Status',
            'title' => 'Title',
            'text' => 'Text',
            'price' => 'Price',
            'deadline' => 'Deadline',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery|CategoryQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(User::class, ['id' => 'customer_id']);
    }

    /**
     * Gets query for [[Executor]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getExecutor()
    {
        return $this->hasOne(User::class, ['id' => 'executor_id']);
    }

    /**
     * Gets query for [[Feedbacks]].
     *
     * @return \yii\db\ActiveQuery|FeedbackQuery
     */
    public function getFeedbacks()
    {
        return $this->hasMany(Feedback::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Files]].
     *
     * @return \yii\db\ActiveQuery|FileQuery
     */
    public function getFiles()
    {
        return $this->hasMany(File::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Responses]].
     *
     * @return \yii\db\ActiveQuery|ResponseQuery
     */
    public function getResponses()
    {
        return $this->hasMany(Response::class, ['task_id' => 'id']);
    }

    public static function getNewTasksQuery(): \yii\db\ActiveQuery
    {
        return self::find()
        ->select([
            'task.*',
            'category.name as category_name',
            'category.label as category_label',
            'city.name as city'
        ])
        ->join('INNER JOIN', 'category', 'task.category_id = category.id')
        ->join('INNER JOIN', 'user', 'task.customer_id = user.id')
        ->join('INNER JOIN', 'city', 'user.city_id = city.id')
        ->where(['task.status' => BaseTask::STATUS_NEW])
        ->asArray();
    }
}
