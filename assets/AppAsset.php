<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/normalize.css',
        'css/style.css',
    ];
    public $js = [
        ['https://api-maps.yandex.ru/2.1/?lang=ru_RU', 'position' => \yii\web\View::POS_HEAD],
        'https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js',
        'js/main.js',
        'js/autocomplete.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
