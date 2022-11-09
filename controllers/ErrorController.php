<?php
namespace app\controllers;

use yii\web\Controller;
use Yii;

class ErrorController extends Controller
{
    public function actionIndex()
    {
        $this->layout = 'error';
        $exception = Yii::$app->errorHandler->exception;

        if ($exception !== null) {
            return $this->render('index', ['exception' => $exception]);
        }
    }
}