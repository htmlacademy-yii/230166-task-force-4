<?php

/** @var yii\web\View $this */
/** @var app\models\forms\SettingsForm $settingsForm */

use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<main class="main-content main-content--left container">

    <?= $this->render('_nav') ?>

    <div class="my-profile-form">
        <?php $form = ActiveForm::begin([
            'id' => 'settings-form'
        ]); ?>
            <h3 class="head-main head-regular">Смена пароля</h3>
            <?= $form->field($securityForm, 'currentPassword')->passwordInput(); ?>
            <?= $form->field($securityForm, 'newPassword')->passwordInput(); ?>
            <?= $form->field($securityForm, 'repeatPassword')->passwordInput(); ?>
            <?= Html::submitButton('Заменить пароль', ['class' => 'button button--blue']) ?>
        <? ActiveForm::end(); ?>
    </div>
</main>