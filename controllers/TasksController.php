<?php

namespace app\controllers;

use yii\data\Pagination;
use Yii;
use app\models\Task;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use app\models\forms\FilterForm;
use app\models\Category;
use app\models\City;
use app\models\Response;
use app\models\User;

class TasksController extends SecuredController
{


    // public function behaviors()
    // {
    //     return [
    //         'access' => [
    //             'class' => AccessControl::class,
    //             'rules' => [
    //                 [
    //                     'allow' => false,
    //                     'actions' => [''],
    //                     'matchCallback' => function($rule, $action)
    //                     {
    //                         $id = Yii::$app->user->getId();

    //                         if ($id) {
    //                             $user = User::findOne($id);
    //                             return $user->is_executor;
    //                         }

    //                         return false;
    //                     }
    //                 ]
    //             ]
    //         ]
    //     ];
    // }

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
        $pageSize = 2;
        $this->view->title = 'Список задач';
        $filterForm = new FilterForm();
        $query = Task::getNewTasksQuery();
        $categories = Category::getMapIdsToLabels();

        if (Yii::$app->request->getIsPost()) {
            $filterForm->load(Yii::$app->request->post());

            if ($filterForm->validate()) {
                $query = $filterForm->getQueryWithFilters();
            }
        }

        $pages = new Pagination([
            'totalCount' => $query->count(),
            'pageSize' => $pageSize,
            'forcePageParam' => false,
            'pageSizeParam' => false
        ]);

        $tasks = $query->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('index', compact('tasks', 'pages', 'filterForm', 'categories'));
    }

    public function actionView($id)
    {
        $task = Task::findOne($id);

        if (!$task) {
            throw new NotFoundHttpException("Контакт с ID $id не найден");
        }

        // $responses = Response::find()->where(['task_id' => $task['id']])->asArray()->all();
        // var_dump($responses);

        return $this->render('view', compact('task'));
    }

    public function actionAddTask()
    {
        $task = new Task();
        $categories = Category::getMapIdsToLabels();
        $city = City::findOne(1);
        $currentUserId = Yii::$app->user->identity->id;

        if (Yii::$app->request->getIsPost()) {
            $task->load(Yii::$app->request->post());

            if ($task->validate()) {
                $task->customer_id = $currentUserId;
                $task->save(false);

                return $this->redirect('/tasks/view/' . $task['id']);
            }
        }

        return $this->render('add-task', compact('task', 'city', 'categories'));
    }

}