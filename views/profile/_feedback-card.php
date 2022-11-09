<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\helpers\ArrayHelper;
    use app\components\StarsWidget;
    use app\components\AvatarWidget;
?>

<div class="response-card">
    <?= AvatarWidget::widget([
            'src' => ArrayHelper::getValue($feedback, 'author.avatar'),
            'width' => 120,
            'height' => 127,
            'alt' => 'Фото заказчика'
        ])
    ?>
    <div class="feedback-wrapper">
        <p class="feedback"><?= Html::encode(ArrayHelper::getValue($feedback, 'message')) ?></p>
        <p class="task">
            Задание «
            <?= Html::a(Html::encode(ArrayHelper::getValue($feedback, 'task.title')),
                    Url::toRoute(['/tasks/view/', 'taskId' => ArrayHelper::getValue($feedback, 'task_id')]),
                    ['class' => 'link link--small']
                )
            ?>
            » выполнено
        </p>
    </div>
    <div class="feedback-wrapper">
        <?= StarsWidget::widget(['class' => 'stars-rating small', 'rating' => ArrayHelper::getValue($feedback, 'rating')]) ?>
        <p class="info-text">
            <span class="current-time">
                <?= Html::encode(get_relative_date(ArrayHelper::getValue($feedback, 'created_at'))) ?>
            </span>
            назад
        </p>
    </div>
</div>