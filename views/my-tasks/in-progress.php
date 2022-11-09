<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>

<main class="main-content container">
    <div class="left-menu">
        <h3 class="head-main head-task">Мои задания</h3>
        <ul class="side-menu-list">
            <li class="side-menu-item">
                <?= Html::a('Новые', Url::to('/my-tasks/new', true), ['class' => 'link link--nav']) ?>
            </li>
            <li class="side-menu-item side-menu-item--active">
                <?= Html::a('В процессе', Url::to('/my-tasks/in-progress', true), ['class' => 'link link--nav']) ?>
            </li>
            <li class="side-menu-item">
                <?= Html::a('Закрытые', Url::to('/my-tasks/finished', true), ['class' => 'link link--nav']) ?>
            </li>
        </ul>
    </div>
    <div class="left-column left-column--task">
        <h3 class="head-main head-regular">Задания в процессе</h3>
        <?php if ($inProgressTasks) : ?>
            <?php foreach ($inProgressTasks as $task) : ?>
                <?= $this->render('../shared/_task-card', compact('task')) ?>
            <? endforeach; ?>
        <? else : ?>
            <p class="caption">Список пуст</p>
        <? endif; ?>
    </div>
</main>