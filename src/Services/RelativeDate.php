<?php

namespace Taskforce\Services;

use Yii;
use DateTime;

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
        $now = Yii::$app->formatter->asTimestamp(new DateTime('now'));
        $postDate = Yii::$app->formatter->asTimestamp($date);
        $dif = abs($now - $postDate);

        var_dump(date('Y-m-d H:i:s', $postDate));
        var_dump(date('Y-m-d H:i:s'));

        if ($dif < 3600 ) {
            return Yii::$app->inflection->pluralize(floor($dif / 60), 'минута');
        }

        if ($dif < 86400) {
            return Yii::$app->inflection->pluralize(floor($dif / 3600), 'час');
        }

        if ($dif < 604800) {
            return Yii::$app->inflection->pluralize(floor($dif / 86400), 'день');
        }

        if ($dif < 3024000) {
            return Yii::$app->inflection->pluralize(floor($dif / 604800), 'неделя');
        }

        return Yii::$app->inflection->pluralize(floor($dif / 3024000), 'месяц');
    }
}