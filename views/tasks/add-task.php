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

            <div class="form-group autoComplete_wrapper">
                <?= $form->field($task, 'city')
                    ->input('search', [
                        'id' => 'city_name',
                        'dir' => 'ltr',
                        'spellcheck' => 'false',
                        'autocorrect' => 'off',
                        'autocomplete' => 'off',
                        'autocapitalize' => 'off'
                    ]);
                ?>
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

            <?= $form->field($task, 'file', [
                    'template' => '{input}{label}{error}',
                    'labelOptions' => [
                        'class' => 'new-file'
                    ]
                ])
                ->fileInput([
                    'hidden' => '',
                    'id' => 'button-input'
                ]);
            ?>

            <input type="submit" class="button button--blue" value="Опубликовать">

        <?php ActiveForm::end(); ?>
    </div>
</main>

<script>
    const autoCompleteJS = new autoComplete({
        selector: '#city_name',
        data: {
            src: ["<?= implode('", "', $cities) ?>"],
            cache: true,
        },
        resultItem: {
            highlight: true
        },
        events: {
            input: {
                selection: (event) => {
                    const selection = event.detail.selection.value;
                    autoCompleteJS.input.value = selection;
                }
            }
        }
    });
</script>