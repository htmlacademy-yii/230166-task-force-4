<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use TaskForce\Actions\ActionQuit;

?>

<section class="pop-up pop-up--refusal pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Отказ от задания</h4>
        <p class="pop-up-text">
            <b>Внимание!</b><br>
            Вы собираетесь отказаться от выполнения этого задания.<br>
            Это действие плохо скажется на вашем рейтинге и увеличит счетчик проваленных заданий.
        </p>
        <?= Html::a(ActionQuit::LABEL, Url::to([
                '/tasks/refuse',
                'taskId' => ArrayHelper::getValue($task, 'id'),
                'userId' => ArrayHelper::getValue($currentUser, 'id')
            ]),
            [
                'class' => 'button button--pop-up button--orange'
            ])
        ?>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>