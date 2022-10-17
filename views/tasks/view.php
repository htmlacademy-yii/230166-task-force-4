<?php
    use yii\helpers\Html;
?>

<main class="main-content container">
    <div class="left-column">
        <div class="head-wrapper">
            <h3 class="head-main">
                <?= Html::encode($task['title']) ?>
            </h3>
            <p class="price price--big">
                <?= Html::encode($task['price']) ?> ₽
            </p>
        </div>

        <p class="task-description">
            <?= Html::encode($task['text']) ?>
        </p>

        <a href="#" class="button button--blue action-btn" data-action="act_response">Откликнуться на задание</a>
        <a href="#" class="button button--orange action-btn" data-action="refusal">Отказаться от задания</a>
        <a href="#" class="button button--pink action-btn" data-action="completion">Завершить задание</a>

        <div class="task-map">
            <?= Html::img(Yii::getAlias('@web').'/img/map.png', [
                    'class' => 'map',
                    'width' => '725',
                    'height' => '346',
                    'alt' => 'Новый арбат, 23, к. 1'
                ]);
            ?>
            <p class="map-address town">Москва</p>
            <p class="map-address">Новый арбат, 23, к. 1</p>
        </div>

        <h4 class="head-regular">Отклики на задание</h4>

        <?= $this->render('_response-card'); ?>
    </div>

    <div class="right-column">
        <div class="right-card black info-card">
            <h4 class="head-card">Информация о задании</h4>
            <dl class="black-list">
                <dt>Категория</dt>
                <dd>Уборка</dd>
                <dt>Дата публикации</dt>
                <dd>25 минут назад</dd>
                <dt>Срок выполнения</dt>
                <dd>15 октября, 13:00</dd>
                <dt>Статус</dt>
                <dd>Открыт для новых заказов</dd>
            </dl>
        </div>

        <div class="right-card white file-card">
            <h4 class="head-card">Файлы задания</h4>
            <ul class="enumeration-list">
                <li class="enumeration-item">
                    <a href="#" class="link link--block link--clip">my_picture.jpg</a>
                    <p class="file-size">356 Кб</p>
                </li>
                <li class="enumeration-item">
                    <a href="#" class="link link--block link--clip">information.docx</a>
                    <p class="file-size">12 Кб</p>
                </li>
            </ul>
        </div>
    </div>
</main>