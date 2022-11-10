<?php

/** @var array $tasks */
/** @var array $pages */

use yii\widgets\LinkPager;

?>

<main class="main-content container">
    <div class="left-column">
        <h3 class="head-main head-task">Новые задания</h3>

        <?php if ($tasks) : ?>
            <!-- список задач -->
            <?php foreach ($tasks as $task) : ?>
                <?= $this->render('../shared/_task-card', compact('task')) ?>
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
                        'maxButtonCount' => 3
                    ]);
                ?>
            </div>
        <? else : ?>
            <p class="caption">Список пуст</p>
        <? endif; ?>
    </div>

    <div class="right-column">
       <div class="right-card black">

            <!-- фильтры -->
            <?= $this->render('_filter-form', compact('filterForm', 'categories')) ?>

       </div>
    </div>
</main>