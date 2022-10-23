<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<main class="main-content main-content--left container">
    <div class="left-menu left-menu--edit">
        <h3 class="head-main head-task">Настройки</h3>
        <ul class="side-menu-list">
            <li class="side-menu-item side-menu-item--active">
                <a href="" class="link link--nav">Мой профиль</a>
            </li>
            <li class="side-menu-item">
                <a href="#" class="link link--nav">Безопасность</a>
            </li>
        </ul>
    </div>
    <div class="my-profile-form">

        <?php $form = ActiveForm::begin([
            'id' => 'settings-form'
        ]); ?>
            <h3 class="head-main head-regular">Мой профиль</h3>
            <div class="photo-editing">
                <div>
                    <p class="form-label">Аватар</p>
                    <?= Html::img(Yii::$app->user->identity->avatar, [
                            'class' => 'avatar-preview',
                            'width' => '83',
                            'height' => '83',
                            'alt' => ''
                        ]);
                    ?>
                </div>

                <?= $form->field($settingsForm, 'imageFile', [
                        'template' => '{input}{label}',
                        'labelOptions' => [
                            'class' => 'button button--black'
                        ]
                    ])
                    ->fileInput(['hidden' => '']);
                ?>
            </div>
            <?= $form->field($settingsForm, 'name')->textInput(); ?>
            <div class="half-wrapper">
                <?= $form->field($settingsForm, 'email')->textInput(); ?>
                <?= $form->field($settingsForm, 'date_of_birth')->input('date'); ?>
            </div>
            <div class="half-wrapper">
                <?= $form->field($settingsForm, 'phone')->textInput(); ?>
                <?= $form->field($settingsForm, 'telegram')->textInput(); ?>
            </div>
            <?= $form->field($settingsForm, 'description')->textarea(); ?>
            <div class="form-group">
                <p class="form-label">Выбор специализаций</p>
                <?= $form->field($settingsForm, 'categories', [
                        'template' => '{input}'
                    ])
                    ->checkboxList($categories, [
                        'class' => 'checkbox-profile',
                        'item' => function($index, $label, $name, $checked, $value) {
                            $checked = $checked ? 'checked' : '';
                            return "<label class='control-label'><input type='checkbox' name='{$name}' value='{$value}' {$checked}>{$label}</label>";
                        }
                    ]);
                ?>
            </div>
            <input type="submit" class="button button--blue" value="Сохранить">
        <? ActiveForm::end(); ?>
    </div>
</main>