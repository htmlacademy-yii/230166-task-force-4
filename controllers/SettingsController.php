<?php

namespace app\controllers;

use Yii;
use app\models\forms\SettingsForm;
use app\models\Category;
use yii\web\Response;
use yii\web\UploadedFile;

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

                return $this->redirect('/tasks');
            }
        }

        return $this->render('index', compact('settingsForm', 'categories'));
    }
}