<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<section class="modal enter-form form-modal j-enter-vk-modal" style="display: block;">
    <h2>Вход через ВК</h2>
    <?php $form = ActiveForm::begin(); ?>
        <p>
            <?= $form->field($authClientForm, 'city', [
                    'labelOptions' => [
                        'class' => 'form-modal-description',
                    ],
                ])
                ->input('text', [
                    'id' => 'city_name',
                    'class' => 'enter-form-email input input-middle',
                    'dir' => 'ltr',
                    'spellcheck' => 'false',
                    'autocorrect' => 'off',
                    'autocomplete' => 'off',
                    'autocapitalize' => 'off'
                ]);
            ?>
        </p>
        <p>
            <div class="form-group">
                <?= $form->field($authClientForm, 'role')->checkbox(); ?>
            </div>
        </p>
        <?= Html::submitButton('Зарегистрироваться', ['class' => 'button']) ?>
    <?php ActiveForm::end(); ?>
    <button class="form-modal-close j-form-modal-close" type="button">Закрыть</button>
</section>

<div class="overlay" style="display: block;"></div>

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