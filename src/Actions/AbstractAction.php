<?php

namespace TaskForce\Actions;

use TaskForce\Models\BaseTask;
use yii\base\Action;

abstract class AbstractAction extends Action
{
    const NAME = self::NAME;
    const LABEL = self::LABEL;

    /**
     * check проверяет права пользователя
     *
     * @param Task $task - объект класса Task
     * @param $currentUserId -id текущего пользователя
     *
     * @return bool
     */
    abstract public static function check(BaseTask $task, int $currentUserId): bool;
}

