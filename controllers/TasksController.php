<?php

namespace app\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;
use app\models\Task;
use app\models\Category;
use app\models\User;
use app\models\File;
use app\models\forms\FilterForm;
use app\models\forms\AddFeedbackForm;
use app\models\forms\AddResponseForm;
use app\models\forms\AddTaskForm;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\web\Response;

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
            },
            'denyCallback' => function($rule, $action) {
                throw new NotFoundHttpException('У вас нет прав для создания задачи');
            }
        ];
        array_unshift($rules['access']['rules'], $rule);

        return $rules;
    }

    /**
     * действия для отклика и начала, отказа, завершения задачи
    */
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
     * выводит новые задачи
     *
     * @return string
     */
    public function actionIndex(): string
    {
        // var_dump(\Yii::$app->authManager);
        $pageSize = 4;
        $filterForm = new FilterForm();
        $query = Task::getNewTaskQuery();
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

    /**
     * выводит задачу
     *
     * @param  int $taskId
     * @return string
     */
    public function actionView(int $taskId): string
    {
        $task = Task::getTaskById($taskId);
        $responses = Task::getAllResponsesAsArray($task);
        $currentUser = User::getCurrentUser();
        $files = File::getTaskFilesAsArray($task);
        $addFeedbackForm = new AddFeedbackForm();
        $addResponseForm = new AddResponseForm();
        $response = Task::getExecutorResponse($task, $currentUser);

        return $this->render('view', compact('task', 'responses', 'response', 'currentUser', 'addFeedbackForm', 'addResponseForm', 'files'));
    }

    /**
     * выводит форму с добавлением задачи
     *
     * @return string
     */
    public function actionAddTask(): string|Response
    {
        $addTaskForm = new AddTaskForm();
        $categories = Category::getMapIdsToLabels();
        $currentUser = User::getCurrentUser();
        $defaultCategory = 1;
        $files = [];

        if (Yii::$app->request->getIsPost()) {
            $addTaskForm->load(Yii::$app->request->post());
            $addTaskForm->file = UploadedFile::getInstance($addTaskForm, 'file');

            if ($addTaskForm->validate()) {
                $task = new Task();
                $task->title = ArrayHelper::getValue($addTaskForm, 'title');
                $task->text = ArrayHelper::getValue($addTaskForm, 'text');
                $task->category_id = ArrayHelper::getValue($addTaskForm, 'category_id') ?? $defaultCategory;
                $task->customer_id = ArrayHelper::getValue($currentUser, 'id');
                $task->price = ArrayHelper::getValue($currentUser, 'price');
                $task->deadline = ArrayHelper::getValue($currentUser, 'deadline');

                // добавляем строку с полной локацией
                $task->location = Task::getLocation(
                    ArrayHelper::getValue($addTaskForm, 'city'),
                    ArrayHelper::getValue($addTaskForm, 'district'),
                    ArrayHelper::getValue($addTaskForm, 'street')
                );

                // добавляем координаты локации
                $geoCoder = new GeoCoderController($task->location);
                $task->lat = $geoCoder->getLat();
                $task->lng = $geoCoder->getLng();

                $transaction = Yii::$app->db->beginTransaction();

                if ($task->save(false)) {
                    if ($addTaskForm->file) {
                        $file = new File;
                        $path = '/uploads/file-' . uniqid() . '.' . $addTaskForm->file->extension;

                        if ($addTaskForm->file->saveAs('@webroot' . $path)) {
                            $file->task_id = ArrayHelper::getValue($task, 'id');
                            $file->url = $path;
                            $file->name = $addTaskForm->file->name;
                            $file->size = $addTaskForm->file->size;
                            $file->save(false);
                        }
                    }

                    // var_dump($addTaskForm->file->name);
                    $transaction->commit();
                    $this->redirect('/tasks');
                } else {
                    throw new \Exception('Задача не сохранилась');
                }
            }
        }

        return $this->render('add-task', compact('addTaskForm', 'categories', 'currentUser', 'files'));
    }
}