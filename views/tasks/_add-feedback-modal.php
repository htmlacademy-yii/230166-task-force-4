<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<section class="pop-up pop-up--completion pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Завершение задания</h4>
        <p class="pop-up-text">
            Вы собираетесь отметить это задание как выполненное.
            Пожалуйста, оставьте отзыв об исполнителе и отметьте отдельно, если возникли проблемы.
        </p>
        <div class="completion-form pop-up--form regular-form">
            <?php $form = ActiveForm::begin([
                    'id' => 'completion-form',
                    'fieldConfig' => [
                        'options' => [
                            'tag' => false,
                        ]
                    ]
                ])
            ?>
                <div class="form-group">
                    <?= $form->field($addFeedbackForm, 'message')->textarea(); ?>
                </div>

                <p class="completion-head control-label">Оценка работы</p>
                <div class="stars-rating big active-stars"><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span></div>
                <?= $form->field($addFeedbackForm, 'rating', ['template' => '{input}{error}'])->textInput(['hidden' => '', 'id' => 'createreviewform-rate']); ?>

                <?= Html::submitButton('Завершить', ['class' => 'button button--pop-up button--blue']) ?>
            <? ActiveForm::end(); ?>
        </div>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>