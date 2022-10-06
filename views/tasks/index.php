<?php
use yii\widgets\LinkPager;
?>

<main class="main-content container">
    <div class="left-column">
        <h3 class="head-main head-task">Новые задания</h3>

        <!-- список задач -->
        <?php foreach ($tasks as $task) : ?>
            <?= $this->render('_task', ['task' => $task]) ?>
        <? endforeach; ?>

        <!-- пагинация -->
        <div class="pagination-wrapper">
            <?= LinkPager::widget([
                    'pagination' => $pages,
                    'options' => [
                        'class' => 'pagination-list',
                    ],
                    'linkOptions' => [
                        'class' => 'link link--page',
                    ],
                    'pageCssClass' => 'pagination-item',
                    'activePageCssClass' => 'pagination-item--active',
                    'prevPageCssClass' => 'pagination-item mark',
                    'nextPageCssClass' => 'pagination-item mark',
                    'prevPageLabel' => '',
                    'nextPageLabel' => '',
                    'maxButtonCount' => 10
                ]);
            ?>
        </div>
    </div>
    <div class="right-column">
       <div class="right-card black">
           <div class="search-form">
                <form>
                    <h4 class="head-card">Категории</h4>
                    <div class="form-group">
                        <div class="checkbox-wrapper">
                            <label class="control-label" for="сourier-services">
                                <input type="checkbox" id="сourier-services" checked>
                                Курьерские услуги</label>
                            <label class="control-label" for="cargo-transportation">
                                <input id="cargo-transportation" type="checkbox">
                                Грузоперевозки</label>
                            <label class="control-label" for="translations">
                                <input id="translations" type="checkbox">
                                Переводы</label>
                        </div>
                    </div>
                    <h4 class="head-card">Дополнительно</h4>
                    <div class="form-group">
                        <label class="control-label" for="without-performer">
                            <input id="without-performer" type="checkbox" checked>
                            Без исполнителя</label>
                    </div>
                    <h4 class="head-card">Период</h4>
                    <div class="form-group">
                        <label for="period-value"></label>
                        <select id="period-value">
                            <option>1 час</option>
                            <option>12 часов</option>
                            <option>24 часа</option>
                        </select>
                    </div>
                    <input type="submit" class="button button--blue" value="Искать">
                </form>
           </div>
       </div>
    </div>
</main>