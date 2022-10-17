<?php

namespace app\controllers;

use yii\data\Pagination;
use Yii;
use app\models\Task;
use yii\web\NotFoundHttpException;
use app\models\forms\FilterForm;

class TasksController extends SecuredController
{
    const PAGE_SIZE = 3;

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $this->view->title = 'Список задач';
        $filterForm = new FilterForm();
        $query = Task::getNewTasksQuery();

        if (Yii::$app->request->getIsPost()) {
            $filterForm->load(Yii::$app->request->post());

            if ($filterForm->validate()) {
                $query = $filterForm->getQueryWithFilters();
            }
        }

        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => self::PAGE_SIZE]);
        $tasks = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('index', compact('tasks', 'pages', 'filterForm'));
    }

    public function actionView($id)
    {
        $task = Task::findOne($id);

        if (!$task) {
            throw new NotFoundHttpException("Контакт с ID $id не найден");
        }

        return $this->render('view', compact('task'));
    }

}