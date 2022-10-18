<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
?>

<div class="task-card">
    <div class="header-task">
        <?= Html::a(Html::encode($task['title']), Url::toRoute(['/tasks/view/', 'id' => $task['id']]), ['class' => 'link link--block link--big']) ?>
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
        <?= Html::a('Смотреть Задание', Url::toRoute(['/tasks/view/', 'id' => $task['id']]), ['class' => 'button button--black']) ?>
    </div>
</div>