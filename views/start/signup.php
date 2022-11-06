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
                        ->textInput([
                            'labelOptions' => [
                                'class' => 'control-label',
                            ],
                        ]);
                    ?>
                </div>
                <div class="half-wrapper">
                    <div class="form-group">
                        <?= $form->field($user, 'email')
                            ->textInput([
                                'labelOptions' => [
                                    'class' => 'control-label',
                                ]
                            ]);
                        ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($user, 'city_id')
                            ->dropDownList($cities, [
                                'options' => [
                                    $user->city_id => ['Selected' => true],
                                ]
                            ]);
                        ?>
                    </div>
                </div>
                <div class="half-wrapper">
                    <div class="form-group">
                        <?= $form->field($user, 'password')
                            ->passwordInput([
                                'labelOptions' => [
                                    'class' => 'control-label',
                                ],
                            ]);
                        ?>
                    </div>
                </div>
                <div class="half-wrapper">
                    <div class="form-group">
                        <?= $form->field($user, 'password_repeat')
                            ->passwordInput([
                                'labelOptions' => [
                                    'class' => 'control-label',
                                ],
                            ]);
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <?= $form->field($user, 'role')
                        ->checkbox([
                            'checked' => $user->role === User::ROLE_EXECUTOR,
                            'labelOptions' => [
                                'class' => 'control-label',
                            ],
                        ]);
                    ?>
                </div>

                <input type="submit" class="button button--blue" value="Создать аккаунт">
            <?php ActiveForm::end(); ?>

        </div>
    </div>
</main>