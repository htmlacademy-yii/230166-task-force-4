<?php

use yii\widgets\ActiveForm;

?>

<main class="main-content main-content--center container">
    <div class="add-task-form regular-form">
        <?php $form = ActiveForm::begin([
            'id' => 'add-task'
        ]); ?>

            <h3 class="head-main head-main">Публикация нового задания</h3>

            <div class="form-group">
                <?= $form->field($task, 'title'); ?>
            </div>

            <div class="form-group">
                <?= $form->field($task, 'text')->textarea(); ?>
            </div>

            <div class="form-group">
                <?= $form->field($task, 'category_id')->dropDownList($categories); ?>
            </div>

            <div class="form-group">
                <?= $form->field($city, 'name'); ?>
            </div>

            <div class="half-wrapper">
                <div class="form-group">
                    <?= $form->field($task, 'price')->input('number') ?>
                </div>
                <div class="form-group">
                    <?= $form->field($task, 'deadline')->input('date'); ?>
                </div>
            </div>

            <p class="form-label">Файлы</p>
            <div class="new-file">
               Добавить новый файл
            </div>

            <input type="submit" class="button button--blue" value="Опубликовать">

        <?php ActiveForm::end(); ?>
    </div>
</main>