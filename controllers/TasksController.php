<?php

namespace app\controllers;

use yii\base\Controller;

class TasksController extends Controller
{
    private array $tasks = [
        [
            'title' => 'adfdsf',
            'price' => 'we4rewr',
            'created_at' => 'sfddsf',
            'text' => 'tdsdfdsdf',
        ]
    ];

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'tasks' => $this->tasks,
        ]);
    }

}