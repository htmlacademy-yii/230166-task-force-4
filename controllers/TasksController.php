<?php

namespace app\controllers;

use app\models\forms\FilterForm;
use yii\base\Controller;
use yii\data\Pagination;
use Yii;

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
        $filterForm = new FilterForm();
        $query = $filterForm->getNewTaskQuery();

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

}