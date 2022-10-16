<?php

use yii\widgets\ActiveForm;

?>

<?php $form = ActiveForm::begin([
    'id' => 'login-form',
    // 'template' => '{label}{input}{error}'
]); ?>
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
    <button class="button" type="submit">Войти</button>
<?php ActiveForm::end(); ?>
