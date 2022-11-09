<?php

use app\models\User;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<main class="container container--registration">
    <div class="center-block">
        <div class="registration-form regular-form">
            <?php $form = ActiveForm::begin(); ?>
                <h3 class="head-main head-task">Регистрация нового пользователя</h3>
                <div class="form-group">
                    <?= $form->field($signupForm, 'name')->textInput(); ?>
                </div>
                <div class="half-wrapper">
                    <?= $form->field($signupForm, 'email')->textInput(); ?>
                    <?= $form->field($signupForm, 'city')
                        ->input('text', [
                            'id' => 'city_name',
                            'dir' => 'ltr',
                            'spellcheck' => 'false',
                            'autocorrect' => 'off',
                            'autocomplete' => 'off',
                            'autocapitalize' => 'off'
                        ]);
                    ?>
                </div>
                <div class="half-wrapper">
                    <?= $form->field($signupForm, 'password', ['inputOptions' => ['autocomplete' => 'off']])->passwordInput(); ?>
                </div>
                <div class="half-wrapper">
                    <?= $form->field($signupForm, 'password_repeat', ['inputOptions' => ['autocomplete' => 'off']])->passwordInput(); ?>
                </div>
                <?= $form->field($signupForm, 'role')->checkbox(); ?>
                <?= Html::submitButton('Создать аккаунт', ['class' => 'button button--blue']) ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</main>

<script>
    const autoCompleteJS = new autoComplete({
        selector: '#city_name',
        data: {
            src: ["<?= implode('", "', $cities) ?>"],
            cache: true,
        },
        resultItem: {
            highlight: true
        },
        events: {
            input: {
                selection: (event) => {
                    const selection = event.detail.selection.value;
                    autoCompleteJS.input.value = selection;
                }
            }
        }
    });
</script>