<?php

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\components\StarsWidget;
use app\components\AvatarWidget;


?>

<main class="main-content container">
    <div class="left-column">

        <h3 class="head-main">
            <?= Html::encode($user['name']) ?>
        </h3>

        <div class="user-card">
            <div class="photo-rate">

                <?= AvatarWidget::widget(['src' => ArrayHelper::getValue($user, 'avatar')]) ?>

                <div class="card-rate">
                    <?= StarsWidget::widget(['className' => 'stars-rating big', 'rating' => $user['rating']]) ?>
                    <span class="current-rate">
                        <?= Html::encode($user['rating']); ?>
                    </span>
                </div>
            </div>
            <?php if (ArrayHelper::getValue($user, 'description')) : ?>
                <p class="user-description">
                    <?= Html::encode($user['description']) ?>
                </p>
            <? endif; ?>
        </div>

        <div class="specialization-bio">
            <div class="specialization">
                <p class="head-info">Специализации</p>
                <?php if ($user['categories']) : ?>
                    <ul class="special-list">
                        <?php foreach ($user['categories'] as $category) : ?>
                            <li class="special-item">
                                <a href="#" class="link link--regular"><?= Html::encode($category['label']) ?></a>
                            </li>
                        <? endforeach; ?>
                    </ul>
                <? else : ?>
                    <div class="caption">Пока нет cпециализаций</div>
                <? endif; ?>
            </div>

            <div class="bio">
                <p class="head-info">Био</p>
                <p class="bio-info">
                    <?php if ($user['city']) : ?>
                        <?php if ($user['city']['address']) : ?>
                            <span class="country-info"><?= Html::encode($user['city']['address']) ?></span>
                        <? else : ?>
                            <span class="town-info"><?= Html::encode($user['city']['name']) ?></span>
                        <? endif; ?>
                    <? endif; ?>
                    <?php if ($user['date_of_birth']) : ?>
                        <span class="age-info"><?= (int) filter_var(Yii::$app->formatter->asRelativeTime(Html::encode($user['date_of_birth']))) ?></span> лет
                    <? endif; ?>
                </p>
            </div>
        </div>

        <?php if ($feedbacks && $user['is_executor']) : ?>
            <h4 class="head-regular">Отзывы заказчиков</h4>
            <?php foreach ($user['feedbacks'] as $feedback) : ?>
                <?= $this->render('_feedback-card', compact('feedback')); ?>
            <? endforeach; ?>
        <? elseif ($user['is_executor']) : ?>
            <div class="caption">Нет отзывов</div>
        <? endif; ?>
    </div>

    <div class="right-column">
        <?php if ($user['is_executor']) : ?>
            <div class="right-card black">
                <h4 class="head-card">Статистика исполнителя</h4>
                <dl class="black-list">
                    <dt>Всего заказов</dt>
                    <dd><?= Html::encode($user['tasks_count']['all']) ?> выполнено, <?= Html::encode($user['tasks_count']['failed']) ?> провалено</dd>
                    <dt>Место в рейтинге</dt>
                    <dd><?= ArrayHelper::getValue($user, 'rate') ?> место</dd>
                    <dt>Дата регистрации</dt>
                    <dd><?= Yii::$app->formatter->asDatetime(Html::encode($user['created_at'])) ?></dd>
                    <dt>Статус</dt>
                    <dd>Открыт для новых заказов</dd>
                </dl>
            </div>
        <? else : ?>
            <div class="right-card black">
                <h4 class="head-card">Статистика заказчика</h4>
                <dl class="black-list">
                    <dt>Всего задач</dt>
                    <dd><?= Html::encode($user['tasks_count']['all']) ?> создано, <?= Html::encode($user['tasks_count']['failed']) ?> провалено</dd>
                    <dt>Дата регистрации</dt>
                    <dd><?= Yii::$app->formatter->asDatetime(Html::encode($user['created_at'])) ?></dd>
                </dl>
            </div>
        <? endif; ?>

        <div class="right-card white">
            <h4 class="head-card">Контакты</h4>
            <ul class="enumeration-list">
                <?php if ($user['phone']) : ?>
                    <li class="enumeration-item">
                        <?= Html::a(Html::encode($user['phone']), Url::to('tel:' . Html::encode($user['phone'])), ['class' => 'link link--block link--phone']) ?>
                    </li>
                <? endif; ?>

                <?php if ($user['email']) : ?>
                    <li class="enumeration-item">
                        <?= Yii::$app->formatter->asEmail(Html::encode($user['email']), ['class' => 'link link--block link--email']) ?>
                    </li>
                <? endif; ?>

                <?php if ($user['phone']) : ?>
                    <li class="enumeration-item">
                        <?= Html::a(Html::encode($user['telegram']), Url::to('https://t.me/' . Html::encode($user['telegram'])), ['class' => 'link link--block link--tg']) ?>
                    </li>
                <? endif; ?>
            </ul>
        </div>
    </div>
</main>