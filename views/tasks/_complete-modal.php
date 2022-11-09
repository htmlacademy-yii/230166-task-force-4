<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

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
                    'action' =>  Url::to([
                        '/tasks/complete',
                        'taskId' => ArrayHelper::getValue($task, 'id'),
                        'customerId' => ArrayHelper::getValue($task, 'customer_id'),
                        'executorId' => ArrayHelper::getValue($task, 'executor_id'),
                    ])
                ])
            ?>
                <?= $form->field($addFeedbackForm, 'message')->textarea(); ?>
                <p class="completion-head control-label">Оценка работы</p>
                <div class="stars-rating big active-stars"><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span></div>
                <?= $form->field($addFeedbackForm, 'rating', ['template' => '{input}{error}'])->textInput(['hidden' => '', 'class' => 'active-stars-input', 'id' => 'createreviewform-rate']); ?>

                <?= Html::submitButton('Завершить', ['class' => 'button button--pop-up button--blue']) ?>
            <? ActiveForm::end(); ?>
        </div>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>