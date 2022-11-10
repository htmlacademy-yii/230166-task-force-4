<?php

/** @var Exception$exception */

use yii\helpers\Html;

?>

<main class="container">
    <div class="error">
        <h1 class="error-title"><?= Html::encode($exception->getName()) ?> <?= Html::encode($exception->status) ?></h1>

        <div class="error-message">
            <?= nl2br(Html::encode($exception->getMessage())) ?>
        </div>
    </div>
</main>