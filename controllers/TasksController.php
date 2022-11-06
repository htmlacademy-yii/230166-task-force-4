<?php

namespace app\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;
use app\models\Task;
use app\models\Category;
use app\models\City;
use app\models\Feedback;
use app\models\Response;
use app\models\User;
use app\models\forms\FilterForm;
use app\models\forms\AddFeedbackForm;
use app\models\forms\AddResponseForm;
use yii\web\NotFoundHttpException;
use TaskForce\Models\BaseTask;
use TaskForce\Actions\AbstractAction;
use TaskForce\Actions\ActionRefuse;
use TaskForce\Actions\ActionComplete;
use TaskForce\Actions\ActionQuit;
use TaskForce\Actions\ActionRespond;
use TaskForce\Actions\ActionStart;

class TasksController extends SecuredController
{
    public function behaviors()
    {
        $rules = parent::behaviors();
        $rule = [
            'allow' => false,
            'actions' => ['add'],
            'roles' => ['@'],
            'matchCallback' => function ($rule, $action) {
                return (Yii::$app->user->identity->role === User::ROLE_EXECUTOR);
            }
        ];
        array_unshift($rules['access']['rules'], $rule);

        return $rules;
    }

    public function actions()
    {
        return [
            'respond' => [
                'class' => 'TaskForce\Actions\ActionRespond',
            ],
            'start' => [
                'class' => 'TaskForce\Actions\ActionStart',
            ],
            'refuse' => [
                'class' => 'TaskForce\Actions\ActionRefuse',
            ],
            'quit' => [
                'class' => 'TaskForce\Actions\ActionQuit',
            ],
            'complete' => [
                'class' => 'TaskForce\Actions\ActionComplete',
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
        $pageSize = 4;
        $this->view->title = 'Список задач';
        $filterForm = new FilterForm();
        $query = Task::getQueryWithNewTasks();
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

    public function actionView($taskId)
    {
        $task = Task::getTaskById($taskId);
        $responses = Task::getAllResponses($task);

        $currentUser = User::getCurrentUser();
        $addFeedbackForm = new AddFeedbackForm();
        $addResponseForm = new AddResponseForm();
        $baseTask = new BaseTask(ArrayHelper::getValue($task, 'customer_id'));

        var_dump(BaseTask::getAvailableActions($task, $currentUser));
        var_dump($currentUser->role);

        if (Yii::$app->request->getIsPost()) {
            if ($addFeedbackForm->load(Yii::$app->request->post())) {
                $feedback = new Feedback();
                $feedback->message = $addFeedbackForm['message'];
                $feedback->rating = 4;
                $feedback->save(false);
            }

            if ($addResponseForm->load(Yii::$app->request->post())) {
                $response = new Response();
                $response->task_id = $taskId;
                $response->user_id = $currentUser['id'];
                $response->message = $addResponseForm['message'];
                $response->price = $addResponseForm['price'];
                $response->save(false);
            }
        }

        return $this->render('view', compact('task', 'responses', 'currentUser', 'addFeedbackForm', 'addResponseForm'));
    }

    public function actionAddTask()
    {
        $task = new Task();
        $city = new City();
        $categories = Category::getMapIdsToLabels();
        $currentUserId = Yii::$app->user->identity->id;

        if (Yii::$app->request->getIsPost()) {
            if ($task->load(Yii::$app->request->post()) && $task->validate()) {
                $task->customer_id = $currentUserId;
                $task->save(false);
            }

            if ($city->load(Yii::$app->request->post()) && $city->validate()) {
                $geoCoder = new GeoCoderController($city['name']);
                $city->name = $geoCoder->getName();
                $city->address = $geoCoder->getAddress();
                $city->lat = $geoCoder->getLat();
                $city->lng = $geoCoder->getLng();
                $city->save(false);

                $task->city_id = $city['id'];
                $task->save(false);
            }
        }

        return $this->render('add-task', compact('task', 'city', 'categories'));
    }
}