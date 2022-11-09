<?php

namespace app\models;

use Yii;
use TaskForce\Models\BaseTask as BaseTask;
use yii\web\NotFoundHttpException;
use app\models\User;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property string|null $created_at
 * @property string|null $status
 * @property string $title
 * @property string $text
 * @property int|null $price
 * @property string|null $deadline
 * @property int $customer_id
 * @property int|null $executor_id
 * @property int $category_id
 * @property string|null $location
 * @property float|null $lat
 * @property float|null $lng
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
            [['status'], 'string'],
            [['title', 'text', 'customer_id', 'category_id'], 'required'],
            [['price', 'customer_id', 'executor_id', 'category_id'], 'integer'],
            [['lat', 'lng'], 'number'],
            [['title'], 'string', 'max' => 100],
            [['text', 'location'], 'string', 'max' => 1000],
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
            'status' => 'Status',
            'title' => 'Title',
            'text' => 'Text',
            'price' => 'Price',
            'deadline' => 'Deadline',
            'customer_id' => 'Customer ID',
            'executor_id' => 'Executor ID',
            'category_id' => 'Category ID',
            'location' => 'Location',
            'lat' => 'Lat',
            'lng' => 'Lng',
        ];
    }

    public static function getTaskById($taskId)
    {
        $task = self::find()->joinWith(['category'])->where(['task.id' => $taskId])->limit(1)->one();

        if (!$task) {
            throw new NotFoundHttpException("Контакт с задачей с ID $taskId не найден");
        }

        return $task;
    }

    public static function getAllResponses(Task $task): array
    {
        return Response::find()->where(['task_id' => ArrayHelper::getValue($task, 'id')])->asArray()->all();
    }

    public static function getNewTasksForCurrentUser(): ?array
    {
        $currentUser = User::getCurrentUser();

        if ($currentUser->role === User::ROLE_EXECUTOR) {
            return self::find()->joinWith(['category'])->where(['executor_id' => $currentUser->id, 'status' => BaseTask::STATUS_NEW])->asArray()->all();
        } else {
            return self::find()->joinWith(['category'])->where(['customer_id' => $currentUser->id, 'status' => BaseTask::STATUS_NEW])->asArray()->all();
        }
    }

    public static function getProgressTasksForCurrentUser(): ?array
    {
        $currentUser = User::getCurrentUser();

        if ($currentUser->role === User::ROLE_EXECUTOR) {
            return self::find()->joinWith(['category'])->where(['executor_id' => $currentUser->id, 'status' => BaseTask::STATUS_INPROGRESS])->asArray()->all();
        } else {
            return self::find()->joinWith(['category'])->where(['customer_id' => $currentUser->id, 'status' => BaseTask::STATUS_INPROGRESS])->asArray()->all();
        }
    }

    public static function getFinishedTasksForCurrentUser(): ?array
    {
        $currentUser = User::getCurrentUser();

        if ($currentUser->role === User::ROLE_EXECUTOR) {
            return self::find()->joinWith(['category'])->where(['executor_id' => $currentUser->id, 'status' => BaseTask::STATUS_DONE])->asArray()->all();
        } else {
            return self::find()->joinWith(['category'])->where(['customer_id' => $currentUser->id, 'status' => BaseTask::STATUS_DONE])->asArray()->all();
        }
    }

    public static function getLocation($city, $district = null, $street = null)
    {
        return implode(', ', array_filter([$city, $district, $street]));
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(User::class, ['id' => 'customer_id']);
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
     * Gets query for [[Feedbacks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFeedbacks()
    {
        return $this->hasMany(Feedback::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Files]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(File::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Responses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponses()
    {
        return $this->hasMany(Response::class, ['task_id' => 'id']);
    }
}