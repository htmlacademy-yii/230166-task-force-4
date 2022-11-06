<?php
    use Yii;
    use yii\helpers\Html;
    use yii\helpers\ArrayHelper;

    $price = ArrayHelper::getValue($task, 'price');
    $text = ArrayHelper::getValue($task, 'text');
?>

<main class="main-content container">
    <div class="left-column">
        <div class="head-wrapper">
            <h3 class="head-main">
                <?= Html::encode($task['title']) ?>
            </h3>

            <?php if($price) : ?>
                <p class="price price--big">
                    <?= Html::encode($price) ?>&nbsp;<span class="regular-font">₽</span>
                </p>
            <? endif; ?>
        </div>

        <?php if($text) : ?>
            <p class="task-description">
                <?= Html::encode($text) ?>
            </p>
        <? endif; ?>

        <?php if(ArrayHelper::getValue($currentUser, 'is_executor') && !ArrayHelper::getValue($task, 'executor_id')) : ?>
            <button class="button button--blue action-btn" data-action="act_response" type="button">Откликнуться на задание</button>
        <? endif; ?>

        <button class="button button--blue action-btn" data-action="act_response" type="button">Откликнуться на задание</button>

        <?php if(ArrayHelper::getValue($currentUser, 'is_executor') && ArrayHelper::getValue($task, 'executor_id') === Yii::$app->user->getId()) : ?>
            <a href="#" class="button button--orange action-btn" data-action="refusal">Отказаться от задания</a>
        <? endif; ?>

        <?php if(!ArrayHelper::getValue($currentUser, 'is_executor') && ArrayHelper::getValue($task, 'customer_id') === Yii::$app->user->getId()) : ?>
            <a href="#" class="button button--pink action-btn" data-action="completion">Завершить задание</a>
        <? endif; ?>

        <?php if (ArrayHelper::getValue($task, 'city')) : ?>
            <div class="task-map">
                <script type="text/javascript">
                    ymaps.ready(init);
                    var myMap;
                    function init(){
                        var myMap = new ymaps.Map("map", {
                            center: [<?= ArrayHelper::getValue($task, 'city.lat') ?>, <?= ArrayHelper::getValue($task, 'city.lng') ?>],
                            zoom: 10,
                            scrollZoom: false,
                            controls: [],
                        });
                    }
                </script>
                <div id="map" style="width: 725px; height: 346px;"></div>
                <?php if (ArrayHelper::getValue($task, 'city.address')) : ?>
                    <?= Html::encode($task['city']['address']) ?>
                <? else : ?>
                    <p class="map-address town"><?= Html::encode($task['city']['name']) ?></p>
                <? endif; ?>
            </div>
        <? endif; ?>

        <?php if (ArrayHelper::getValue($currentUser, 'id') == ArrayHelper::getValue($task, 'customer_id')) : ?>
            <h4 class="head-regular">Отклики на задание</h4>
            <?php if ($responses) : ?>
                <?php foreach($responses as $response) : ?>
                    <?= $this->render('_response-card', compact('response', 'task', 'currentUser')); ?>
                <? endforeach; ?>
            <? else : ?>
                <p class="caption">Список пуст</p>
            <? endif; ?>
        <? endif; ?>

        <h4 class="head-regular">Отклики на задание</h4>
            <?php if ($responses) : ?>
                <?php foreach($responses as $response) : ?>
                    <?= $this->render('_response-card', compact('response', 'task', 'currentUser')); ?>
                <? endforeach; ?>
            <? else : ?>
                <p class="caption">Список пуст</p>
            <? endif; ?>
    </div>

    <div class="right-column">
        <div class="right-card black info-card">
            <h4 class="head-card">Информация о задании</h4>
            <dl class="black-list">
                <dt>Категория</dt>
                <dd><?= Html::encode($task['category']['label']) ?></dd>
                <dt>Дата публикации</dt>
                <dd><?= Yii::$app->formatter->asDate(Html::encode($task['created_at'])) ?></dd>
                <dt>Срок выполнения</dt>
                <dd><?= Yii::$app->formatter->asDate(Html::encode($task['deadline'])) ?></dd>
                <dt>Статус</dt>
                <dd><?= Html::encode($task['status']) ?></dd>
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

<?= $this->render('_add-response-modal', compact('addResponseForm', 'task', 'currentUser')); ?>
<?= $this->render('_add-feedback-modal', compact('addFeedbackForm', 'task', 'currentUser')); ?>
<?= $this->render('_refusal-modal', compact('task', 'task', 'currentUser')); ?>

<div class="overlay"></div>
