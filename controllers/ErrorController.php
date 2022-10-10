<?php

namespace app\controllers;

use yii\base\Controller;

class ErrorController extends Controller
{

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $this->layout = false;
        $message = '';
        $name = '';

        return $this->render('index', compact('message', 'name'));
    }
}