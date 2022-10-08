<?php

namespace app\controllers;

use app\models\Task;
use yii\base\Controller;
use yii\data\Pagination;

class TasksController extends Controller
{
    const PAGE_SIZE = 3;

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $this->view->title = 'Список задач';

        $query = Task::find()
            ->select([
                'task.id',
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
            ->asArray();

        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => self::PAGE_SIZE]);
        $tasks = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('index', compact('tasks', 'pages'));
    }

}