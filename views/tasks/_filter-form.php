<?php

/** @var app\models\forms\FilterForm $filterForm */

use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<div class="search-form">
    <?php $form = ActiveForm::begin([
            'id' => 'filter-form',
            'fieldConfig' => ['options' => ['tag' => false]]
        ]);
    ?>
        <h4 class="head-card">Категории</h4>
        <div class="form-group">
            <?= $form->field($filterForm, 'categories', [
                    'template' => '{input}'
                ])
                ->checkboxList($categories, [
                    'class' => 'checkbox-wrapper',
                    'item' => function($index, $label, $name, $checked, $value) {
                        $checked = $checked ? 'checked' : '';
                        return "<label class='control-label'><input type='checkbox' name='{$name}' value='{$value}' {$checked}>{$label}</label>";
                    }
                ]);
            ?>
        </div>

        <h4 class="head-card">Дополнительно</h4>
        <div class="form-group">
            <?= $form->field($filterForm, 'noExecutor', [
                    'template' => '{input}{label}',
                ])
                ->checkbox([
                    'checked' => $filterForm->noExecutor,
                    'labelOptions' => [
                        'class' => 'control-label',
                    ]
                ]);
            ?>
        </div>

        <h4 class="head-card">Период</h4>
        <div class="form-group">
            <?=
                $form->field($filterForm, 'period', [
                    'template' => '{input}',
                ])
                ->dropDownList($filterForm->periodAttributeLabels(), [
                    'options'=>[
                        $filterForm->period => ['Selected'=>true]
                    ]
                ]);
            ?>
        </div>

        <?= Html::submitButton('Искать', ['class' => 'button button--blue']) ?>

    <?php ActiveForm::end(); ?>
</div>
