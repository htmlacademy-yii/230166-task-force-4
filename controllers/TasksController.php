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
        $query->from('task')->orderBy('created_at ASC');
        $tasks = $query->all();

        return $this->render('index', [
            'tasks' => $tasks,
        ]);
    }

}