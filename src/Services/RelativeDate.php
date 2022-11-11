<?php

namespace Taskforce\Services;

use Taskforce\Services\NounPluralForm;

class RelativeDate
{
    /**
     * Функция принимает дату, переводит в Unix и возвращает сколько прошло времени
     * относительно от текущего времени в минутах, часах, неделях, месяцах
     *
     * @param  string
     * @return string
    */
    public static function get($date)
    {
        $cur_date = time();
        $post_date = strtotime($date);
        $dif_date = abs($cur_date - $post_date);

        if ($dif_date < 3600 && $dif_date > 60) {
            $minuts = floor($dif_date / 60);
            return $minuts . ' ' . NounPluralForm::get($minuts, 'минута', 'минуты', 'минут');
        }

        if ($dif_date < 86400) {
            $hours = floor($dif_date / 3600);
            return $hours . ' ' . NounPluralForm::get($hours, 'час', 'часа', 'часов');
        }

        if ($dif_date < 604800) {
            $days = floor($dif_date / 86400);
            return $days . ' ' . NounPluralForm::get($days, 'день', 'дня', 'дней');
        }

        if ($dif_date < 3024000) {
            $weeks = floor($dif_date / 604800);
            return $weeks . ' ' . NounPluralForm::get($weeks, 'неделя', 'недели', 'недель');
        }

        $months = floor($dif_date / 3024000);
        return $months . ' ' . NounPluralForm::get($months, 'месяц', 'месяца', 'месяцев');
    }
}