<?php

namespace app\controllers;

use Yii;
use app\models\forms\SettingsForm;
use app\models\Category;
use app\models\forms\SecurityForm;
use yii\web\Response;
use yii\web\UploadedFile;
use app\models\User;

class SettingsController extends SecuredController
{
    /**
     * показываем страницу с нстройками
     *
     * @return string|Response
     */
    public function actionIndex(): string|Response
    {
        $settingsForm = new SettingsForm();
        $categories = Category::getMapIdsToLabels();

        if (Yii::$app->request->getIsPost()) {
            $settingsForm->load(Yii::$app->request->post());
            $settingsForm->imageFile = UploadedFile::getInstance($settingsForm, 'imageFile');

            if ($settingsForm->validate()) {
                $settingsForm->updateUser();

                return $this->redirect(['/tasks']);
            }
        }

        return $this->render('index', compact('settingsForm', 'categories'));
    }

    public function actionSecurity()
    {
        $securityForm = new SecurityForm();
        $userId = User::getCurrentUserId();
        $user = User::findOne($userId);

        if (Yii::$app->request->getIsPost()) {
            $securityForm->load(Yii::$app->request->post());

            if ($securityForm->validate()) {
                $user->password = Yii::$app->security->generatePasswordHash($securityForm->newPassword);
                $user->save(false);

                return $this->redirect(['/start/logout']);
            }
        }

        return $this->render('security', compact('securityForm'));
    }
}

