<?php

use yii\widgets\ActiveForm;
use app\models\Category;

?>

<div class="search-form">
    <?php $form = ActiveForm::begin([
            'id' => 'filter-form',
            'fieldConfig' => [
                'options' => [
                    'tag' => false,
                ]
            ]
        ]);
    ?>
        <h4 class="head-card">Категории</h4>
        <div class="form-group">
            <?= $form->field($filterForm, 'categories', [
                    'template' => '{input}'
                ])
                ->checkboxList(Category::getCategories(), [
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

        <input type="submit" class="button button--blue" value="Искать">

    <?php ActiveForm::end(); ?>
</div>
