<?php

/** @var yii\web\View $this */
/** @var string $content */

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Menu;
use app\assets\AppAsset;
use app\components\AvatarWidget;
use app\models\User;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => '@web/favicon.ico']);

$is_executor = (Yii::$app->user->identity && Yii::$app->user->identity->role === User::ROLE_EXECUTOR) ?? null;

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
        <?php if (Yii::$app->user->isGuest) : ?>
            <a href="<?= Url::to('/', true) ?>" class="header-logo">
                <?= Html::img(Yii::getAlias('@web').'/img/logotype.png', [
                        'class' => 'logo-image',
                        'width' => '227',
                        'height' => '60',
                        'alt' => 'taskforce'
                    ]);
                ?>
            </a>
        <? else : ?>
            <a href="<?= Url::to('/tasks', true) ?>" class="header-logo">
                <?= Html::img(Yii::getAlias('@web').'/img/logotype.png', [
                        'class' => 'logo-image',
                        'width' => '227',
                        'height' => '60',
                        'alt' => 'taskforce'
                    ]);
                ?>
            </a>
        <? endif; ?>
        <?php if (!Yii::$app->user->isGuest) : ?>
            <div class="nav-wrapper">
                <?= Menu::widget([
                        'items' => [
                            ['label' => 'Новое', 'url' => ['/tasks/index']],
                            ['label' => 'Мои задания', 'url' => ['/my-tasks/new']],
                            ['label' => 'Создать задание', 'url' => ['/tasks/add-task'], 'visible' => !$is_executor],
                            ['label' => 'Настройки', 'url' => ['/settings/index']],
                        ],
                        'options' => [
                            'class' => 'nav-list'
                        ],
                        'itemOptions' => [
                            'class' => 'list-item'
                        ],
                        'linkTemplate' => '<a href="{url}" class="link link--nav">{label}</a>',
                        'activateItems' => true,
                        'activateParents' => true,
                        'activeCssClass' => 'list-item--active',
                    ]);
                ?>
            </div>
        <? endif; ?>
    </nav>
    <?php if (!Yii::$app->user->isGuest) : ?>
        <div class="user-block">
            <?= AvatarWidget::widget([
                    'userId' => $is_executor ? Yii::$app->user->getId() : null,
                    'src' => Yii::$app->user->identity->avatar,
                    'width' => 55,
                    'height' => 55,
                    'alt' => 'Аватар'
                ])
            ?>
            <div class="user-menu">
                <p class="user-name">
                    <?php if ($is_executor) : ?>
                        <?= Html::a(Html::encode(Yii::$app->user->identity->name), Url::to(['/profile', 'executorId' => Yii::$app->user->getId()]), ['class' => 'link']) ?>
                    <? else : ?>
                        <span class="link"><?= Html::encode(Yii::$app->user->identity->name) ?></span>
                    <? endif; ?>
                </p>
                <div class="popup-head">
                    <ul class="popup-menu">
                        <li class="menu-item">
                            <?= Html::a('Настройки', Url::to('/settings', true), ['class' => 'link']) ?>
                        </li>
                        <li class="menu-item">
                            <?= Html::a('Связаться с нами', Url::to('/', true), ['class' => 'link']) ?>
                        </li>
                        <li class="menu-item">
                            <?= Html::a('Выход из системы', Url::to('/start/logout', true), ['class' => 'link']) ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    <? endif; ?>
</header>

<?= $content ?>

<?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>
