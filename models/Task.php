<?php

namespace app\models;

use Yii;
use TaskForce\Models\Task as BaseTask;
use yii\web\NotFoundHttpException;
use app\models\User;

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
 * @property int|null $price
 * @property string|null $deadline
 * @property int|null $city_id
 *
 * @property City $city
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
            [['created_at', 'deadline', 'date_of_birth'], 'safe'],
            [['customer_id', 'executor_id', 'category_id'], 'integer'],
            [['status'], 'string'],
            [['title', 'text'], 'required'],
            [['price', 'id'], 'number'],
            [['title'], 'string', 'max' => 100],
            [['text'], 'string', 'max' => 500],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['customer_id' => 'id']],
            [['executor_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['executor_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
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
            'category_id' => 'Category Id',
            'city_id' => 'City Id',
            'status' => 'Status',
            'title' => 'Опишите суть работы',
            'text' => 'Подробности задания',
            'price' => 'Бюджет',
            'deadline' => 'Срок исполнения',
        ];
    }

    public static function getQueryWithNewTasks(): \yii\db\ActiveQuery
    {
        return self::find()
            ->joinWith(['category', 'city'])
            ->where(['task.status' => 'new'])
            ->asArray();
    }

    public static function getTaskById($taskId)
    {
        $task = self::find()
            ->joinWith(['city', 'category'])
            ->where(['task.id' => $taskId])
            ->limit(1)
            ->asArray()
            ->one();

        if (!$task) {
            throw new NotFoundHttpException("Контакт с задачей с ID $taskId не найден");
        }

        return $task;
    }

    public static function getAllResponses($task)
    {
        return Response::find()
        ->where(['task_id' => $task['id']])
        ->joinWith('user')
        ->asArray()
        ->all();
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery|CityQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
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
}
