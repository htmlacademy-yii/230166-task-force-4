<?php

namespace app\controllers;

use app\models\Task;
use yii\base\Controller;
use yii\db\Query;

class TasksController extends Controller
{
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $tasks = Task::find()
            ->select([
                'task.created_at',
                'task.title',
                'task.price',
                'task.text',
                'task.status',
                'category.name as category_name',
                'category.icon as category_icon',
                'city.name as city'
            ])
            ->join('INNER JOIN', 'category', 'task.category_id = category.id')
            ->join('INNER JOIN', 'user', 'task.customer_id = user.id')
            ->join('INNER JOIN', 'city', 'user.city_id = city.id')
            ->where(['task.status' => 'new'])
            ->asArray()
            ->all();

        $this->view->title = 'Список задач';
        return $this->render('index', [
            'tasks' => $tasks,
        ]);
    }

}