<?php
    use Yii;
    use yii\helpers\Html;
    use yii\helpers\ArrayHelper;
    use TaskForce\Actions\ActionQuit;
    use TaskForce\Actions\ActionRespond;
    use TaskForce\Actions\ActionComplete;
    use TaskForce\Models\BaseTask;

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

        <?php if(ArrayHelper::isIn('respond', BaseTask::getAvailableActions($task, $currentUser))) : ?>
            <button class="button button--blue action-btn" data-action="act_response" type="button">
                <?= Html::encode(ActionRespond::LABEL) ?>
            </button>
        <? endif; ?>

        <?php if (ArrayHelper::isIn('quit', BaseTask::getAvailableActions($task, $currentUser))) : ?>
            <button class="button button--orange action-btn" data-action="refusal" type="button">
                <?= Html::encode(ActionQuit::LABEL) ?>
            </button>
        <? endif; ?>

        <?php if(ArrayHelper::isIn('complete', BaseTask::getAvailableActions($task, $currentUser))) : ?>
            <button class="button button--pink action-btn" data-action="completion" type="button">
                <?= Html::encode(ActionComplete::LABEL) ?>
            </button>
        <? endif; ?>

        <?php if (ArrayHelper::getValue($task, 'location')) : ?>
            <div class="task-map">
                <script type="text/javascript">
                    ymaps.ready(init);
                    var myMap;
                    function init(){
                        var myMap = new ymaps.Map("map", {
                            center: [<?= ArrayHelper::getValue($task, 'lat') ?>, <?= ArrayHelper::getValue($task, 'lng') ?>],
                            zoom: 10,
                            scrollZoom: false,
                            controls: [],
                        });
                    }
                </script>
                <div id="map" style="width: 725px; height: 346px;"></div>
                <?php if (ArrayHelper::getValue($task, 'location')) : ?>
                    <p class="map-address town"><?= Html::encode(ArrayHelper::getValue($task, 'location')) ?></p>
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

        <?php if ($response && ArrayHelper::getValue($response, 'executor_id') === Yii::$app->user->getId()) : ?>
            <h4 class="head-regular">Ваш отклик</h4>
            <?= $this->render('_response-card', compact('response', 'task', 'currentUser')); ?>
        <? endif; ?>
    </div>

    <div class="right-column">
        <div class="right-card black info-card">
            <h4 class="head-card">Информация о задании</h4>
            <dl class="black-list">
                <dt>Категория</dt>
                <dd><?= Html::encode(ArrayHelper::getValue($task, 'category.label')) ?></dd>
                <dt>Дата публикации</dt>
                <dd><?= Yii::$app->formatter->asDate(Html::encode(ArrayHelper::getValue($task, 'created_at'))) ?></dd>
                <dt>Срок выполнения</dt>
                <dd><?= Yii::$app->formatter->asDate(Html::encode(ArrayHelper::getValue($task, 'deadline'))) ?></dd>
                <dt>Статус</dt>
                <dd><?= Html::encode(ArrayHelper::getValue(BaseTask::getStatusesLabels(), ArrayHelper::getValue($task, 'status'))) ?></dd>
            </dl>
        </div>

        <?php if ($files) : ?>
            <div class="right-card white file-card">
                <h4 class="head-card">Файлы задания</h4>
                <ul class="enumeration-list">
                    <?php foreach($files as $file) : ?>
                        <li class="enumeration-item">
                            <a href="<?= ArrayHelper::getValue($currentUser, 'url') ?>" class="link link--block link--clip">my_picture.jpg</a>
                            <p class="file-size">356 Кб</p>
                        </li>
                    <? endforeach; ?>
                </ul>
            </div>
        <? endif ; ?>
    </div>
</main>

<?= $this->render('_respond-modal', compact('addResponseForm', 'task', 'currentUser')); ?>
<?= $this->render('_complete-modal', compact('addFeedbackForm', 'task')); ?>
<?= $this->render('_quit-modal', compact('task', 'currentUser')); ?>

<div class="overlay"></div>
