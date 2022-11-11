<?php

/** @var array $response */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\components\StarsWidget;
use app\components\AvatarWidget;
use app\models\Response;
use Taskforce\Actions\ActionStart;
use Taskforce\Actions\ActionRefuse;
use Taskforce\Models\BaseTask;
use Taskforce\Services\RelativeDate;

?>

<div class="response-card">
    <?= AvatarWidget::widget([
            'userId' => ArrayHelper::getValue($response, 'user_id'),
            'src' => ArrayHelper::getValue($response, 'user_avatar'),
            'imageClass' => 'customer-photo',
            'width' => 146,
            'height' => 156,
            'alt' => 'Фото заказчика'
        ])
    ?>

    <div class="feedback-wrapper">
        <?= Html::a(ArrayHelper::getValue($response, 'user_name'), Url::to(['/profile', 'userId' => ArrayHelper::getValue($response, 'user_id')])) ?>
        <div class="response-wrapper">
            <?= StarsWidget::widget(['className' => 'stars-rating small', 'rating' => ArrayHelper::getValue($response, 'user_rating')]) ?>

            <?php if (ArrayHelper::getValue($response, 'user_count_feedbacks')) : ?>
                <p class="reviews">
                    <?= Yii::$app->inflection->pluralize($response['user_count_feedbacks'], 'отзыв') ?>
                </p>
            <? endif; ?>
        </div>
        <p class="response-message">
            <?= Html::encode(ArrayHelper::getValue($response, 'message')) ?>
        </p>
    </div>

    <div class="feedback-wrapper">
        <?php if (ArrayHelper::getValue($response, 'created_at')) : ?>
            <p class="info-text">
                <span class="current-time">
                    <?= Html::encode(RelativeDate::get($response['created_at'])) ?>
                </span>
                назад
            </p>
        <? endif; ?>

        <?php if (ArrayHelper::getValue($response, 'price')) : ?>
            <p class="price price--small"><?= Html::encode(ArrayHelper::getValue($response, 'price')) ?> ₽</p>
        <? endif; ?>
    </div>

    <div class="button-popup">
        <?php if(ArrayHelper::isIn('start', BaseTask::getAvailableActions($task, $currentUser))
            && ArrayHelper::getValue($response, 'status') !== Response::STATUS_REFUSE) :
        ?>
            <?= Html::a(ActionStart::LABEL, Url::to([
                    '/tasks/start',
                    'taskId' => ArrayHelper::getValue($task, 'id'),
                    'executorId' => ArrayHelper::getValue($response, 'user_id')
                ]),
                [
                    'class' => 'button button--blue button--small'
                ])
            ?>
        <? endif; ?>

        <?php if(ArrayHelper::isIn('refuse', BaseTask::getAvailableActions($task, $currentUser))) : ?>
            <?php if (ArrayHelper::getValue($response, 'status') === Response::STATUS_REFUSE) : ?>
                <span class = "button button--orange button--small">Отказано</span>
            <? else : ?>
                <?= Html::a(ActionRefuse::LABEL, Url::to([
                        '/tasks/refuse',
                        'taskId' => ArrayHelper::getValue($task, 'id'),
                        'executorId' => ArrayHelper::getValue($response, 'user_id')
                    ]),
                    [
                        'class' => 'button button--orange button--small'
                    ])
                ?>
            <? endif; ?>
        <? endif; ?>
    </div>
</div>