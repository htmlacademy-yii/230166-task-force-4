<?php

namespace app\controllers;

use app\models\forms\FilterForm;
use yii\base\Controller;
use yii\data\Pagination;
use Yii;
use app\models\Task;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class TasksController extends Controller
{
    const PAGE_SIZE = 3;

        /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

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

    public function actionView(int $id): string
    {
        $id = intval($id);

        var_dump($id);
        $task = Task::findOne(1);

        if (!$task) {
            throw new NotFoundHttpException("Контакт с ID $id не найден");
        }

        return $this->render('view', compact('task'));
    }

}