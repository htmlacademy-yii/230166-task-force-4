<?php

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
                    <?= $form->field($user, 'name', [
                            'labelOptions' => [
                                'class' => 'control-label',
                            ],
                        ])
                        ->textInput();
                    ?>
                </div>
                <div class="half-wrapper">
                    <div class="form-group">
                        <?= $form->field($user, 'email', [
                                'labelOptions' => [
                                    'class' => 'control-label',
                                ],
                            ])
                            ->textInput();
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
                        <?= $form->field($user, 'password', [
                                'labelOptions' => [
                                    'class' => 'control-label',
                                ],
                            ])
                            ->passwordInput();
                        ?>
                    </div>
                </div>
                <div class="half-wrapper">
                    <div class="form-group">
                        <?= $form->field($user, 'password_repeat', [
                            'labelOptions' => [
                                'class' => 'control-label',
                            ],
                            ])->passwordInput();
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <?= $form->field($user, 'is_executor')
                        ->checkbox([
                            'checked' => $user->is_executor,
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