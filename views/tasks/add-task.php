<?php

use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<main class="main-content main-content--center container">
    <div class="add-task-form regular-form">
        <?php $form = ActiveForm::begin([
                'id' => 'add-task'
            ]);
        ?>
            <h3 class="head-main head-main">Публикация нового задания</h3>
                <?= $form->field($addTaskForm, 'title'); ?>
                <?= $form->field($addTaskForm, 'text')->textarea(); ?>
                <?= $form->field($addTaskForm, 'category_id')->dropDownList($categories); ?>

                <div class="inline-fields">
                    <?= $form->field($addTaskForm, 'city')
                        ->textInput([
                            'id' => 'location',
                            'class' => 'location-icon',
                            'value' => ArrayHelper::getValue($currentUser, 'city.name'),
                            'readonly' => true
                        ]);
                    ?>
                    <?= $form->field($addTaskForm, 'district')
                        ->textInput([
                            'id' => 'district',
                            'class' => 'location-icon',
                        ]);
                    ?>
                    <?= $form->field($addTaskForm, 'street')
                        ->textInput([
                            'id' => 'street',
                            'class' => 'location-icon',
                        ]);
                    ?>
                </div>
            <div class="inline-fields">
                <?= $form->field($addTaskForm, 'price')->input('number') ?>
                <?= $form->field($addTaskForm, 'deadline')->input('date'); ?>
            </div>
            <p class="form-label">Файлы</p>
            <?= $form->field($addTaskForm, 'file', [
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
            <?= Html::submitButton('Опубликовать', ['class' => 'button button--blue']) ?>
        <?php ActiveForm::end(); ?>
    </div>
</main>
<!--
<script>
    const autoCompleteJS = new autoComplete({
        selector: '#city_name',
        data: {
            src: [""],
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
</script> -->