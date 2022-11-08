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
use app\models\File;
use app\models\forms\FilterForm;
use app\models\forms\AddFeedbackForm;
use app\models\forms\AddResponseForm;
use Exception;
use TaskForce\Models\BaseTask;
use yii\web\UploadedFile;

class TasksController extends SecuredController
{
    public function behaviors()
    {
        $rules = parent::behaviors();
        $rule = [
            'allow' => false,
            'actions' => ['add-task'],
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
        $query = Task::find()->joinWith(['category', 'city'])->where(['task.status' => 'new'])->asArray();
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

        if (Yii::$app->request->getIsPost()) {
            if ($addFeedbackForm->load(Yii::$app->request->post()) && $addFeedbackForm->validate()) {
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    $feedback = new Feedback();
                    $feedback->message = $addFeedbackForm['message'];
                    $feedback->rating = $addFeedbackForm['message'];
                    $feedback->save(false);

                    $currentUser->count_feedbacks += 1;
                    $currentUser->rating = Feedback::find()->where(['executor_id' => $task->executor_id])->sum('rating') / $currentUser->count_feedbacks;
                    $currentUser->save();

                    $transaction->commit();
                } catch(Exception $e) {
                    $transaction->rollBack();
                    throw $e;
                } catch (\Throwable $e) {
                    $transaction->rollBack();
                    throw $e;
                }
            }

            if ($addResponseForm->load(Yii::$app->request->post()) && $addResponseForm->validate()) {
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
        $categories = Category::getMapIdsToLabels();
        $currentUserId = Yii::$app->user->identity->id;
        $cities = ArrayHelper::getColumn(City::find()->asArray()->all(), 'name');

        if (Yii::$app->request->getIsPost()) {
            $task->load(Yii::$app->request->post());
            $task->file = UploadedFile::getInstance($task, 'file');

            if ($task->validate()) {
                $task->customer_id = $currentUserId;
                $task->city_id = City::find()->select('id')->where(['name' => $task['city']]);

                if ($task->file) {
                    $file = new File;
                    $path = '/uploads/file-' . uniqid() . '.' . $task->file->extension;

                    if ($task->file->saveAs('@webroot' . $path)) {
                        $file->task_id = $task->id;
                        $file->user_id = $currentUserId;
                        $file->url = $path;
                        $file->save(false);
                    }
                }

                $task->save(false);
                $this->redirect('/tasks');
            }
        }

        return $this->render('add-task', compact('task', 'categories', 'cities'));
    }
}