<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => '@web/favicon.ico']);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="utf-8">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header class="page-header">
    <nav class="main-nav">
        <a href='/' class="header-logo">
            <?= Html::img(Yii::getAlias('@web').'/img/logotype.png', [
                    'class' => 'logo-image',
                    'width' => '227',
                    'height' => '60',
                    'alt' => 'taskforce'
                ]);
            ?>
        </a>
        <div class="nav-wrapper">
            <ul class="nav-list">
                <li class="list-item list-item--active">
                    <?= Html::a('Новое', Url::to('/tasks', true), ['class' => 'link link--nav']) ?>
                </li>
                <li class="list-item">
                    <?= Html::a('Мои задания', Url::to('/profile/tasks', true), ['class' => 'link link--nav']) ?>
                </li>
                <li class="list-item">
                    <?= Html::a('Создать задание', Url::to('/tasks/add-task', true), ['class' => 'link link--nav']) ?>
                </li>
                <li class="list-item">
                    <?= Html::a('Настройки', Url::to('/profile/settings', true), ['class' => 'link link--nav']) ?>
                </li>
            </ul>
        </div>
    </nav>
    <div class="user-block">
        <a href="#">
            <?= Html::img(Yii::getAlias('@web').'/img/man-glasses.png', [
                    'class' => 'user-photo',
                    'width' => '55',
                    'height' => '55',
                    'alt' => 'Аватар'
                ]);
            ?>
        </a>
        <div class="user-menu">
            <p class="user-name">Василий</p>
            <div class="popup-head">
                <ul class="popup-menu">
                    <li class="menu-item">
                        <?= Html::a('Настройки', Url::to('/profile/settings', true), ['class' => 'link']) ?>
                    </li>
                    <li class="menu-item">
                        <?= Html::a('Связаться с нами', Url::to('/', true), ['class' => 'link']) ?>
                    </li>
                    <li class="menu-item">
                        <?= Html::a('Выход из системы', Url::to('/profile/logout', true), ['class' => 'link']) ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>

<?= $content ?>

<?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>
