<?php
    use Yii;
    use yii\helpers\Html;
?>

<div class="<?= Html::encode($className) ?>">
    <?php if ($rating == 0) : ?>
        <span>&nbsp;</span>
        <span>&nbsp;</span>
        <span>&nbsp;</span>
        <span>&nbsp;</span>
        <span>&nbsp;</span>
    <? elseif ($rating > 0 && $rating <= 1) : ?>
        <span class="fill-star">&nbsp;</span>
        <span>&nbsp;</span>
        <span>&nbsp;</span>
        <span>&nbsp;</span>
        <span>&nbsp;</span>
    <? elseif($rating > 1 && $rating <= 2) : ?>
        <span class="fill-star">&nbsp;</span>
        <span class="fill-star">&nbsp;</span>
        <span>&nbsp;</span>
        <span>&nbsp;</span>
        <span>&nbsp;</span>
    <? elseif($rating > 2 && $rating <= 3) : ?>
        <span class="fill-star">&nbsp;</span>
        <span class="fill-star">&nbsp;</span>
        <span class="fill-star">&nbsp;</span>
        <span>&nbsp;</span>
        <span>&nbsp;</span>
    <? elseif($rating > 3 && $rating <= 4) : ?>
        <span class="fill-star">&nbsp;</span>
        <span class="fill-star">&nbsp;</span>
        <span class="fill-star">&nbsp;</span>
        <span class="fill-star">&nbsp;</span>
        <span>&nbsp;</span>
    <? else : ?>
        <span class="fill-star">&nbsp;</span>
        <span class="fill-star">&nbsp;</span>
        <span class="fill-star">&nbsp;</span>
        <span class="fill-star">&nbsp;</span>
        <span class="fill-star">&nbsp;</span>
    <? endif; ?>
</div>