<?php
    use yii\helpers\Html;
?>

<div class="task-card">
    <div class="header-task">
        <a  href="#" class="link link--block link--big"><?= Html::encode($task['title']) ?></a>
        <p class="price price--task">
            <?= Html::encode($task['price']) ?> ₽
        </p>
    </div>

    <p class="info-text">
        <span class="current-time">
            <?= get_relative_date(Html::encode($task['created_at'])) ?>
        </span>
        назад
    </p>

    <p class="task-text">
        <?= Html::encode($task['text'])?>
    </p>

    <div class="footer-task">
        <p class="info-text town-text">
            <?= Html::encode($task['city']) ?>
        </p>
        <p class="info-text category-text">
            <?= Html::encode($task['category_label']) ?>
        </p>
        <a href="#" class="button button--black">
            Смотреть Задание
        </a>
    </div>
</div>