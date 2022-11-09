<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="task-card">
    <div class="header-task">
        <?= Html::a(Html::encode($task['title']), Url::toRoute(['/tasks/view/', 'taskId' => $task['id']]), ['class' => 'link link--block link--big']) ?>
        <?php if ($task['price']) : ?>
            <p class="price price--task">
                <?= Html::encode($task['price']) ?> ₽
            </p>
        <? endif; ?>
    </div>
    <p class="info-text">
        <span class="current-time">
            <?= get_relative_date(Html::encode($task['created_at'])) ?>
        </span>
        назад
    </p>
    <p class="task-text">
        <?= Html::encode($task['text']) ?>
    </p>
    <div class="footer-task">
        <?php if (ArrayHelper::getValue($task, 'location')) : ?>
            <p class="info-text town-text">
                <?= Html::encode(ArrayHelper::getValue($task, 'location')) ?>
            </p>
        <? endif; ?>
        <?php if (ArrayHelper::getValue($task, 'category')) : ?>
            <p class="info-text category-text">
                <?= Html::encode(ArrayHelper::getValue($task, 'category.label')) ?>
            </p>
        <? endif; ?>
        <?= Html::a('Смотреть Задание', Url::toRoute(['/tasks/view/', 'taskId' => $task['id']]), ['class' => 'button button--black']) ?>
    </div>
</div>