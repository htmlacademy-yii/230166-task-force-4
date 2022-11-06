<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

?>

<section class="pop-up pop-up--act_response pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Добавление отклика к заданию</h4>
        <p class="pop-up-text">
            Вы собираетесь оставить свой отклик к этому заданию.
            Пожалуйста, укажите стоимость работы и добавьте комментарий, если необходимо.
        </p>
        <div class="addition-form pop-up--form regular-form">
            <?php $form = ActiveForm::begin([
                    'action' => Url::to([
                        '/tasks/complete',
                        'taskId' => ArrayHelper::getValue($task, 'id'),
                        'userId' => ArrayHelper::getValue($currentUser, 'id')
                    ]),
                ]);
            ?>
                <?= $form->field($addResponseForm, 'message')->textarea(); ?>
                <?= $form->field($addResponseForm, 'price')->textInput(); ?>
                <?= Html::submitButton('Завершить', ['class' => 'button button--pop-up button--blue']) ?>
            <? ActiveForm::end(); ?>
        </div>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>