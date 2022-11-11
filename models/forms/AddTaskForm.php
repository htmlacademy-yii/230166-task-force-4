<?php

namespace app\models\forms;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use app\models\User;
use app\models\Category;
use app\models\File;
use TaskForce\Services\Location\GeoCoder;

class AddTaskForm extends Model
{
    public $title;
    public $text;
    public $category_id;
    public $city;
    public $district;
    public $street;
    public $price;
    public $deadline;
    public $file;

    public function rules()
    {
        return [
            [['title', 'text', 'category_id'], 'required'],
            ['title', 'string', 'max' => 100],
            ['title', 'string', 'min' => 10],
            ['text', 'string', 'max' => 500],
            ['text', 'string', 'min' => 30],
            [['price', 'category_id'], 'integer'],
            ['city', 'validateCity'],
            ['district', 'validateDistrict'],
            ['street', 'validateStreet'],
            ['deadline', 'validateDeadline'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => 'Опишите суть работы',
            'text' => 'Подробности задания',
            'category_id' => 'Категории',
            'city' => 'Город',
            'district' => 'Район',
            'street' => 'Улица',
            'price' => 'Бюджет',
            'deadline' => 'Срок исполнения',
            'file' => 'Файлы'
        ];
    }

    public function validateCity($attribute, $params): void
    {
        $currentUser = User::getCurrentUser();

        if ($this->city !== ArrayHelper::getValue($currentUser, 'city.name')) {
            $this->addError($attribute, 'Город не совпадает с указанным при регистрации');
        }
    }

    public function validateDistrict($attribute, $params)
    {
        $geoCoder = new GeoCoder($this->city . $this->district);

        if (!$geoCoder->getName()) {
            $this->addError($attribute, 'Район не найден');
        }
    }

    public function validateStreet($attribute, $params)
    {
        $geoCoder = new GeoCoder($this->city . ' ' . $this->district . ' ' . $this->street);

        if (!$geoCoder->getName()) {
            $this->addError($attribute, 'Улица не найдена');
        }
    }

    public function validateDeadline($attribute, $params)
    {
        $currentDate = time();
        $deadline = strtotime($this->deadline);

        if ($deadline < $currentDate) {
            $this->addError($attribute, 'Дата не может быть раньше текущего дня');
        }
    }

    public function addFile($task)
    {
        if ($this->file) {
            $file = new File;
            $path = '/uploads/file-' . uniqid() . '.' . $this->file->extension;

            if ($this->file->saveAs('@webroot' . $path)) {
                $file->task_id = ArrayHelper::getValue($task, 'id');
                $file->url = $path;
                $file->name = $this->file->name;
                $file->size = $this->file->size;
                $file->save(false);
            }
        }
    }
}