<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use app\models\Category;
use app\models\Task;
use app\models\Feedback;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string|null $created_at
 * @property int|null $role
 * @property float|null $rating
 * @property int|null $count_feedbacks
 * @property string $email
 * @property string $name
 * @property string $password
 * @property string|null $avatar
 * @property string|null $date_of_birth
 * @property string|null $phone
 * @property string|null $telegram
 * @property string|null $description
 * @property int|null $city_id
 *
 * @property Auth[] $auths
 * @property City $city
 * @property File[] $files
 * @property Response[] $responses
 * @property Task[] $tasks
 * @property UserCategory[] $userCategories
 */
class User extends ActiveRecord implements IdentityInterface
{
    const ROLE_CUSTOMER = 'customer';
    const ROLE_EXECUTOR = 'executor';

    public $password_repeat;
    public $categories;
    public $city;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'date_of_birth'], 'safe'],
            [['count_feedbacks', 'city_id'], 'integer'],
            [['role'], 'string'],
            [['rating'], 'number'],
            [['email', 'name', 'password', 'password_repeat'], 'required', 'message' => 'Это обязательное поле'],
            [['name', 'email'], 'string', 'max' => 40, 'message' => 'Максимальное количество символов 40'],
            [['password', 'avatar', 'telegram'], 'string', 'max' => 200, 'message' => 'Максимальное количество символов 200'],
            [['password'], 'string', 'min' => 6, 'message' => 'Минимальное количество символов 6'],
            [['password'], 'compare'],
            [['phone'], 'match', 'pattern' => '/^[\d]{11}/i', 'message' => 'Номер телефона должен состоять из 11 цифр'],
            [['email'], 'unique', 'message' => 'Пользователь с таким Email уже зарегистрирован'],
            [['description'], 'string', 'max' => 1000, 'message' => 'Максимальное количество символов 1000'],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
            [['city'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city' => 'name']],
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
            'role' => 'Я собираюсь откликаться на заказы',
            'rating' => 'Рейтинг',
            'count_feedbacks' => 'Count Feedbacks',
            'email' => 'Email',
            'name' => 'Ваше имя',
            'password' => 'Пароль',
            'password_repeat' => 'Повтор пароля',
            'avatar' => 'Аватар',
            'date_of_birth' => 'Дата рождения',
            'phone' => 'Телефон',
            'telegram' => 'Telegram',
            'description' => 'Информация о себе',
            'city_id' => 'City ID',
            'city' => 'Город',
        ];
    }

    public static function getUserAsArray($userId)
    {
        $user = self::find()->where(['user.id' => $userId])->joinWith(['city'])->asArray()->limit(1)->one();

        if (!$user) {
            throw new NotFoundHttpException("Контакт с ID $userId не найден");
        }

        return $user;
    }

    public static function getCurrentUser()
    {
        if ($currentUserId = Yii::$app->user->getId()) {
            $currentUser = self::find()->where(['user.id' => $currentUserId])->joinWith(['city'])->limit(1)->one();
        }

        return $currentUser;
    }

    public static function getCategories($user)
    {
        return
            Category::find()
                ->join('INNER JOIN', 'user_category', 'user_category.category_id = category.id')
                ->join('INNER JOIN', 'user', 'user_category.user_id = user.id')
                ->where(['user.id' => $user['id']])
                ->asArray()
                ->all();
    }

    public static function getCategoriesIds($user): ?array
    {
        $categories = self::getCategories($user);

        if ($categories) {
            return ArrayHelper::getColumn($categories, 'id');
        }

        return null;
    }

    public static function getTasks($user)
    {
        if ($user['role'] === self::ROLE_EXECUTOR) {
            return Task::find()->where(['task.executor_id' => $user['id']])->asArray()->all();
        }

        return Task::find()->where(['task.customer_id' => $user['id']])->asArray()->all();
    }

    public static function getRate($user)
    {
        $ids = User::find()->select(['user.id'])->where(['user.role' => self::ROLE_EXECUTOR])->orderBy(['user.rating' => SORT_DESC])->asArray()->all();
        $arrIds = ArrayHelper::getColumn($ids, 'id');

        return array_search($user['id'], $arrIds);
    }

    public static function getFeedbacks($user)
    {
        if ($user['role'] === self::ROLE_EXECUTOR){
            $tasks = Task::find()->where(['task.executor_id' => $user['id'], 'task.status' => 'done'])->asArray()->all();

            foreach ($tasks as $task) {
                $feedback = Feedback::find()->where(['feedback.task_id' => $task['id']])->limit(1)->asArray()->one();
                $feedback['author'] = User::find()->select(['user.id', 'user.avatar'])->where(['user.id' => $task['customer_id']])->asArray()->limit(1)->one();
                $feedback['task']['id'] = $task['id'];
                $feedback['task']['title'] = $task['title'];
                $feedbacks[] = $feedback;
            }
        }

        return null;
    }

    public static function getFeedbacksCount($user)
    {
        if ($user['role'] === self::ROLE_EXECUTOR) {
            return Task::find()->where(['task.executor_id' => $user['id'], 'task.status' => 'done'])->count();
        }
    }

    /**
     * Gets query for [[Auths]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuths()
    {
        return $this->hasMany(Auth::class, ['user_id' => 'id']);
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
     * Gets query for [[Files]].
     *
     * @return \yii\db\ActiveQuery|FileQuery
     */
    public function getFiles()
    {
        return $this->hasMany(File::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Responses]].
     *
     * @return \yii\db\ActiveQuery|ResponseQuery
     */
    public function getResponses()
    {
        return $this->hasMany(Response::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery|TaskQuery
     */
    public function getCustomerTasks()
    {
        return $this->hasMany(Task::class, ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks0]].
     *
     * @return \yii\db\ActiveQuery|TaskQuery
     */
    public function getExecutorTasks()
    {
        return $this->hasMany(Task::class, ['executor_id' => 'id']);
    }

    /**
     * Gets query for [[UserCategories]].
     *
     * @return \yii\db\ActiveQuery|UserCategoryQuery
     */
    public function getUserCategories()
    {
        return $this->hasMany(UserCategory::class, ['user_id' => 'id']);
    }

    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }

    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->password);
    }
}
