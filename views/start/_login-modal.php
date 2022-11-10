<?php

/** @var app\models\forms\LoginForm $loginForm */

use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<section class="modal enter-form form-modal" id="enter-form">
    <h2>Вход на сайт</h2>

    <?php $form = ActiveForm::begin(); ?>
        <p>
            <?= $form->field($loginForm, 'email', [
                    'labelOptions' => [
                        'class' => 'form-modal-description',
                    ],
                ])
                ->textInput([
                    'class' => 'enter-form-email input input-middle',
                ]);
            ?>
        </p>
        <p>
            <?= $form->field($loginForm, 'password', [
                    'labelOptions' => [
                        'class' => 'form-modal-description',
                    ],
                ])
                ->passwordInput([
                    'class' => 'enter-form-email input input-middle',
                ]);
            ?>
        </p>
        <?= Html::submitButton('Войти', ['class' => 'button']) ?>
    <?php ActiveForm::end(); ?>

    <div class="auth-client-title">или войти через ВК</div>


    <?= yii\authclient\widgets\AuthChoice::widget([
                'baseAuthUrl' => ['/start/auth'],
                'popupMode' => false,
            ]) ?>

    <button class="form-modal-close j-form-modal-close" type="button">Закрыть</button>
</section>