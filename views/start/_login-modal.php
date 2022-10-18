<?php

use yii\widgets\ActiveForm;

?>

<section class="modal enter-form form-modal" id="enter-form">
    <h2>Вход на сайт</h2>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form'
    ]); ?>
        <p>
            <?= $form
                ->field($loginForm, 'email')
                ->textInput([
                    'class' => 'enter-form-email input input-middle',
                    'labelOptions' => [
                        'class' => 'form-modal-description',
                    ],
                ]);
            ?>
        </p>
        <p>
            <?= $form
                ->field($loginForm, 'password')
                ->passwordInput([
                    'class' => 'enter-form-email input input-middle',
                    'labelOptions' => [
                        'class' => 'form-modal-description',
                    ],
                ]);
            ?>
        </p>
        <button class="button" type="submit">Войти</button>
    <?php ActiveForm::end(); ?>

    <button class="form-modal-close" type="button">Закрыть</button>
</section>