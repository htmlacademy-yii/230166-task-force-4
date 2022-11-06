<?php

namespace app\models\forms;

use Yii;
use Exception;
use yii\base\Model;
use app\models\User;
use app\models\UserCategory;

class SettingsForm extends Model
{
    public $avatar;
    public $name;
    public $email;
    public $date_of_birth;
    public $phone;
    public $telegram;
    public $description;
    public $categories;
    public $imageFile;

    private $_user;

    public function __construct()
    {
        $this->_user = $this->getUser();

        $this->avatar = $this->_user->avatar;
        $this->name = $this->_user->name;
        $this->email = $this->_user->email;
        $this->date_of_birth = $this->_user->date_of_birth;
        $this->phone = $this->_user->phone;
        $this->telegram = $this->_user->telegram;
        $this->description = $this->_user->description;

        $this->categories = User::getCategoriesIds($this->_user);
    }

    public function attributeLabels()
    {
        return [
            'imageFile' => 'Сменить аватар',
            'name' => 'Ваше имя',
            'email' => 'Email',
            'date_of_birth' => 'Дата рождения',
            'phone' => 'Телефон',
            'telegram' => 'Telegram',
            'description' => 'Информация о себе',
            'categories' => 'Выбор специализаций'
        ];
    }

    public function rules()
    {
        return [
            [['date_of_birth', 'categories'], 'safe'],
            [['name', 'email'], 'required', 'message' => 'Это обязательное поле'],
            [['name', 'email', 'telegram', 'phone', 'description'], 'trim'],
            [['name', 'email'], 'string', 'max' => 40, 'message' => 'Максимальное количество символов 40'],
            [['description'], 'string', 'max' => 1000, 'message' => 'Максимальное количество символов 1000'],
            [['phone'], 'match', 'pattern' => '/^[\d]{11}/i', 'message' => 'Номер телефона должен состоять из 11 цифр'],
            [['telegram'], 'string', 'max' => 200, 'message' => 'Максимальное количество символов 200'],
            ['email', 'validateEmail'],
            ['imageFile', 'file', 'extensions' => 'png, jpg, svg']
        ];
    }

    public function updateUser()
    {

        $this->_user->name = $this->name;
        $this->_user->email = $this->email;
        $this->_user->date_of_birth = $this->date_of_birth;
        $this->_user->phone = $this->phone;
        $this->_user->telegram = $this->telegram;
        $this->_user->description = $this->description;

        if ($this->imageFile) {
            $path = '/uploads/' . uniqid() . '.' . $this->imageFile->extension;

            if ($this->uploadFile($path)) {
                $this->_user->avatar = $path;
            }
        }


        if ($this->categories) {
            $this->updateUserCategories();
        }

        try {
            $this->_user->save(false);
        } catch(Exception $exception) {
            throw new $exception('Пользователь не сохранился');
        }
    }

    public function validateEmail($attribute, $params): void
    {
        if ($this->email !== $this->_user->email) {
            $isRepeatEmail = User::find()->where(['user.email' => $this->email, 'user.id' <> $this->_user->id]);

            if ($isRepeatEmail) {
                $this->addError($attribute, 'Этот email уже зарегистрирован');
            }
        }
    }

    private function uploadFile($fileName): bool
    {
        if ($this->validate()) {
            $this->imageFile->saveAs('@webroot' . $fileName);
            return true;
        } else {
            return false;
        }
    }

    private function updateUserCategories()
    {
        UserCategory::deleteAll($this->_user->id);

        foreach ($this->categories as $category_id) {
            $userCategory = new UserCategory();
            $userCategory->user_id = $this->_user->id;
            $userCategory->category_id = $category_id;
            $userCategory->save(false);
        }
    }

    private function getUser()
    {
        $currentId = Yii::$app->user->getId();
        return $this->_user = User::findOne($currentId);
    }
}