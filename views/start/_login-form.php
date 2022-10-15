<?php

use yii\widgets\ActiveForm;

?>

<?php $form = ActiveForm::begin([
    'id' => 'login-form',
    // 'template' => '{label}{input}{error}'
]); ?>
    <p>
        <?= $form->field($loginForm, 'email')
            ->textInput([
                'class' => 'enter-form-email input input-middle',
                'labelOptions' => [
                    'class' => 'form-modal-description',
                ],
            ]);
        ?>
    </p>
    <p>
        <?= $form->field($loginForm, 'password')
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
