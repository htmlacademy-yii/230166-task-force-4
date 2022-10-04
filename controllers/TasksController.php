<?php

namespace app\controllers;

use yii\base\Controller;
use yii\db\Query;

class TasksController extends Controller
{
    // private array $tasks = [
    //     [
    //         'title' => 'adfdsf',
    //         'price' => 'we4rewr',
    //         'created_at' => 'sfddsf',
    //         'text' => 'tdsdfdsdf',
    //     ]
    // ];

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $query = new Query();

        $query
            ->select([
                'task.created_at',
                'task.title',
                'task.price',
                'task.text',
                'category.name as category_name',
                'category.icon as category_icon',
                'city.name as city'
            ])
            ->from('task')
            ->join('INNER JOIN', 'category', 'task.category_id = category.id')
            ->join('INNER JOIN', 'user', 'task.customer_id = user.id')
            ->join('INNER JOIN', 'city', 'user.city_id = city.id')
            ->where(['task.status' => 'new'])
            ->orderBy('created_at ASC');

        $tasks = $query->all();

        $this->view->title = 'Список задач';
        return $this->render('index', [
            'tasks' => $tasks,
        ]);
    }

}