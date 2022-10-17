<?php

use yii\helpers\Html;

?>

<div class="response-card">
    <?= Html::img(Yii::getAlias('@web').'/img/man-glasses.png', [
            'class' => 'customer-photo',
            'width' => '146',
            'height' => '156',
            'alt' => 'Фото заказчиков'
        ]);
    ?>

    <div class="feedback-wrapper">
        <a href="#" class="link link--block link--big">Астахов Павел</a>
        <div class="response-wrapper">
            <div class="stars-rating small"><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span>&nbsp;</span></div>
            <p class="reviews">2 отзыва</p>
        </div>
        <p class="response-message">
            Могу сделать всё в лучшем виде. У меня есть необходимый опыт и инструменты.
        </p>
    </div>

    <div class="feedback-wrapper">
        <p class="info-text"><span class="current-time">25 минут </span>назад</p>
        <p class="price price--small">3700 ₽</p>
    </div>

    <div class="button-popup">
        <a href="#" class="button button--blue button--small">Принять</a>
        <a href="#" class="button button--orange button--small">Отказать</a>
    </div>
</div>