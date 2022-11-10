<?php

/** @var array $tasks */
/** @var string $status */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\Menu;
use app\models\User;
use TaskForce\Models\BaseMyTasks;

$is_executor = (Yii::$app->user->identity && Yii::$app->user->identity->role === User::ROLE_EXECUTOR) ?? null;

?>

<main class="main-content container">
    <div class="left-menu">
        <h3 class="head-main head-task">Мои задания</h3>
        <?= Menu::widget([
                'items' => [
                    ['label' => 'Новые', 'url' => ['/my-tasks', 'status' => BaseMyTasks::STATUS_NEW], 'visible' => !$is_executor],
                    ['label' => 'В процессе', 'url' => ['/my-tasks', 'status' => BaseMyTasks::STATUS_INPROGRESS]],
                    ['label' => 'Просроченные', 'url' => ['/my-tasks', 'status' => BaseMyTasks::STATUS_LATE], 'visible' => $is_executor],
                    ['label' => 'Закрытые', 'url' => ['/my-tasks', 'status' => BaseMyTasks::STATUS_COMPLETE]],
                ],
                'options' => [
                    'class' => 'side-menu-list'
                ],
                'itemOptions' => [
                    'class' => 'side-menu-item'
                ],
                'linkTemplate' => '<a href="{url}" class="link link--nav">{label}</a>',
                'activateItems' => true,
                'activateParents' => true,
                'activeCssClass' => 'side-menu-item--active',
            ]);
        ?>
    </div>
    <div class="left-column left-column--task">
        <h3 class="head-main head-regular"><?= Html::encode(ArrayHelper::getValue(BaseMyTasks::getStatusesLabels(), $status)) ?></h3>
        <?php if ($tasks) : ?>
            <?php foreach ($tasks as $task) : ?>
                <?= $this->render('../shared/_task-card', compact('task')) ?>
            <? endforeach; ?>
        <? else : ?>
            <p class="caption">Список пуст</p>
        <? endif; ?>
    </div>
</main>