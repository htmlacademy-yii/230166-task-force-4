<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>

<?php if ($userId) : ?>
    <a href="<?= Url::to(['/profile', 'executorId' => $userId]) ?>" class="<?= $linkClass ?>">
        <?= Html::img($src, [
                'imageClass' => $imageClass,
                'width' => $width,
                'height' => $height,
                'alt' => $alt
            ]);
        ?>
    </a>
<? else : ?>
    <?= Html::img($src, [
            'imageClass' => $imageClass,
            'width' => $width,
            'height' => $height,
            'alt' => $alt
        ]);
    ?>
<? endif; ?>