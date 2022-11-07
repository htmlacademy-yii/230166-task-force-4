<?php

use app\models\User;
use yii\widgets\ActiveForm;

?>

<main class="container container--registration">
    <div class="center-block">
        <div class="registration-form regular-form">
            <?php $form = ActiveForm::begin([
                'id' => 'registration-form',
            ]); ?>
                <h3 class="head-main head-task">Регистрация нового пользователя</h3>
                <div class="form-group">
                    <?= $form->field($user, 'name')
                        ->textInput();
                    ?>
                </div>
                <div class="half-wrapper">
                    <div class="form-group">
                        <?= $form->field($user, 'email')
                            ->textInput();
                        ?>
                    </div>
                    <div class="form-group autoComplete_wrapper">
                        <?= $form->field($user, 'city')
                            ->input('search', [
                                'id' => 'city_name',
                                'dir' => 'ltr',
                                'spellcheck' => 'false',
                                'autocorrect' => 'off',
                                'autocomplete' => 'off',
                                'autocapitalize' => 'off'
                            ]);
                        ?>
                    </div>


                </div>
                <div class="half-wrapper">
                    <div class="form-group">
                        <?= $form->field($user, 'password')
                            ->passwordInput();
                        ?>
                    </div>
                </div>
                <div class="half-wrapper">
                    <div class="form-group">
                        <?= $form->field($user, 'password_repeat')
                            ->passwordInput();
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <?= $form->field($user, 'role')
                        ->checkbox([
                            'checked' => $user->role === User::ROLE_EXECUTOR,
                        ]);
                    ?>
                </div>

                <input type="submit" class="button button--blue" value="Создать аккаунт">
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