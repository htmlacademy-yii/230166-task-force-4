<?php

use app\models\User;
use yii\widgets\Menu;

?>

<div class="left-menu left-menu--edit">
    <h3 class="head-main head-task">Настройки</h3>
    <?php if (!User::isAuthClientUser()) : ?>
        <?= Menu::widget([
                'items' => [
                    ['label' => 'Мой профиль', 'url' => ['/settings']],
                    ['label' => 'Безопасность', 'url' => ['/settings/security']],
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
    <? endif; ?>
</div>