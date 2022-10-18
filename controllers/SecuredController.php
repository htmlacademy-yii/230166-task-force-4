<?php
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\User;

abstract class SecuredController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ],
                    [
                        'allow' => false,
                        'matchCallback' => function($rule, $action)
                        {
                            $id = Yii::$app->request->get('id') ?? null;

                            if ($id) {
                                $user = User::findOne($id);
                                return $user->customer_id == Yii::$app->user->getId();
                            }

                            return false;
                        }
                    ]
                ]
            ]
        ];
    }
}