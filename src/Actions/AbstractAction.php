<?php

namespace Taskforce\Actions;

use yii\base\Action;
use app\models\User;
use app\models\Task;

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
    abstract public static function check(Task $task, User $currentUser): bool;
}

