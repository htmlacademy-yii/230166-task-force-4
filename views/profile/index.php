<?php

/** @var array $feedbacks */
/** @var array $categories */
/** @var array $user */

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\components\StarsWidget;
use app\components\AvatarWidget;
use app\models\User;

?>

<main class="main-content container">
    <div class="left-column">
        <h3 class="head-main">
            <?= Html::encode(ArrayHelper::getValue($user, 'name')) ?>
        </h3>
        <div class="user-card">
            <div class="photo-rate">
                <?= AvatarWidget::widget(['src' => ArrayHelper::getValue($user, 'avatar')]) ?>
                <div class="card-rate">
                    <?= StarsWidget::widget(['className' => 'stars-rating big', 'rating' => ArrayHelper::getValue($user, 'rating')]) ?>
                    <span class="current-rate">
                        <?= Html::encode(ArrayHelper::getValue($user, 'rating')); ?>
                    </span>
                </div>
            </div>
            <?php if (ArrayHelper::getValue($user, 'description')) : ?>
                <p class="user-description">
                    <?= Html::encode(ArrayHelper::getValue($user, 'description')) ?>
                </p>
            <? endif; ?>
        </div>

        <div class="specialization-bio">
            <div class="specialization">
                <?php if ($categories) : ?>
                    <p class="head-info">Специализации</p>
                    <ul class="special-list">
                        <?php foreach ($categories as $category) : ?>
                            <li class="special-item">
                                <a href="#" class="link link--regular"><?= Html::encode($category['label']) ?></a>
                            </li>
                        <? endforeach; ?>
                    </ul>
                <? endif; ?>
            </div>

            <div class="bio">
                <p class="head-info">Био</p>
                <p class="bio-info">
                    <span class="town-info"><?= Html::encode(ArrayHelper::getValue($user, 'city.name')) ?></span>
                    <?php if (ArrayHelper::getValue($user, 'date_of_birth')) : ?>
                        <span class="age-info"><?= (int) filter_var(Yii::$app->formatter->asRelativeTime(Html::encode(ArrayHelper::getValue($user, 'date_of_birth')))) ?></span> лет
                    <? endif; ?>
                </p>
            </div>
        </div>

        <?php if ($feedbacks) : ?>
            <h4 class="head-regular">Отзывы заказчиков</h4>
            <?php foreach ($feedbacks as $feedback) : ?>
                <?= $this->render('_feedback-card', compact('feedback')); ?>
            <? endforeach; ?>
        <? endif; ?>
    </div>

    <div class="right-column">
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
                <?php if (!User::isAvailable(ArrayHelper::getValue($user, 'id'))) : ?>
                    <dd>Открыт для новых заказов</dd>
                <? else : ?>
                    <dd>Занят</dd>
                <? endif; ?>
            </dl>
        </div>

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