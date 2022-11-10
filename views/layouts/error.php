<?php

/** @var yii\web\View $this */

use app\assets\ErrorAsset;

ErrorAsset::register($this);

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>

    <?= $content ?>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>