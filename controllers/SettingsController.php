<?php

namespace app\controllers;

use Yii;
use app\models\forms\SettingsForm;
use app\models\Category;
use yii\web\UploadedFile;

class SettingsController extends SecuredController
{
    public function actionIndex()
    {
        $settingsForm = new SettingsForm();
        $categories = Category::getMapIdsToLabels();

        if (Yii::$app->request->getIsPost()) {
            $settingsForm->load(Yii::$app->request->post());
            $settingsForm->imageFile = UploadedFile::getInstance($settingsForm, 'imageFile');

            if ($settingsForm->validate()) {
                $settingsForm->updateUser();

                return $this->redirect('/profile');
            }
        }

        return $this->render('index', compact('settingsForm', 'categories'));
    }
}