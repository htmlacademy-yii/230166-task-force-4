<?php

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\components\StarsWidget;
use app\components\AvatarWidget;
use app\models\Response;
use TaskForce\Actions\ActionStart;
use TaskForce\Actions\ActionRefuse;
use TaskForce\Models\BaseTask;

?>

<div class="response-card">
    <?= AvatarWidget::widget([
            'userId' => ArrayHelper::getValue($response, 'user.id'),
            'src' => ArrayHelper::getValue($response, 'user.avatar'),
            'imageClass' => 'customer-photo',
            'width' => 146,
            'height' => 156,
            'alt' => 'Фото заказчика'
        ])
    ?>

    <div class="feedback-wrapper">
        <?= Html::a(ArrayHelper::getValue($response, 'user.name'), Url::to(['/profile', 'userId' => ArrayHelper::getValue($response, 'user.id')])) ?>
        <div class="response-wrapper">
            <?= StarsWidget::widget(['className' => 'stars-rating small', 'rating' => ArrayHelper::getValue($response, 'user.rating')]) ?>
            <p class="reviews">
                <?= ArrayHelper::getValue($response, 'user.count_feedbacks') ?> <?= get_noun_plural_form(ArrayHelper::getValue($response, 'user.count_feedbacks'), 'отзыв', 'отзыва', 'отзывов') ?>
            </p>
        </div>
        <p class="response-message">
            <?= Html::encode(ArrayHelper::getValue($response, 'message')) ?>
        </p>
    </div>

    <div class="feedback-wrapper">
        <p class="info-text">
            <span class="current-time">
                <?= Html::encode(Yii::$app->formatter->asRelativetime(ArrayHelper::getValue($response, 'created_at'))) ?>
            </span>
            назад
        </p>
        <p class="price price--small"><?= Html::encode(ArrayHelper::getValue($response, 'price')) ?> ₽</p>
    </div>

    <div class="button-popup">
        <?php if(ArrayHelper::isIn('start', BaseTask::getAvailableActions($task, $currentUser)) && $response->status !== Response::STATUS_REFUSE) : ?>
            <?= Html::a(ActionStart::LABEL, Url::to([
                    '/tasks/start',
                    'taskId' => ArrayHelper::getValue($task, 'id'),
                    'userId' => ArrayHelper::getValue($response, 'user.id')
                ]),
                [
                    'class' => 'button button--blue button--small'
                ])
            ?>
        <? endif; ?>

        <?php if(ArrayHelper::isIn('refuse', BaseTask::getAvailableActions($task, $currentUser))) : ?>
            <?php if ($response->status === Response::STATUS_REFUSE) : ?>
                <span class = "button button--orange button--small">Отказано</span>
            <? else : ?>
                <?= Html::a(ActionRefuse::LABEL, Url::to([
                        '/tasks/refuse',
                        'taskId' => ArrayHelper::getValue($task, 'id'),
                        'userId' => ArrayHelper::getValue($response, 'user.id')
                    ]),
                    [
                        'class' => 'button button--orange button--small'
                    ])
                ?>
            <? endif; ?>
        <? endif; ?>
    </div>
</div>