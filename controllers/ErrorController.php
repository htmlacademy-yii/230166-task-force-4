<?php
namespace app\controllers;

use yii\web\Controller;
use Yii;

class ErrorController extends Controller
{
    /**
     * показываем страницу с ошибкой
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $this->layout = 'error';
        $exception = Yii::$app->errorHandler->exception;

        if ($exception !== null) {
            return $this->render('index', ['exception' => $exception]);
        }
    }
}