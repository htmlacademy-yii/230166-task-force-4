<?php

namespace Taskforce\Models;

class BaseMyTasks
{
    const STATUS_NEW = 'new';
    const STATUS_INPROGRESS = 'in-progress';
    const STATUS_COMPLETE = 'done';
    const STATUS_LATE = 'late';

    /**
     * getStatusesLabels
     *
     * @return array
     */
    public static function getStatusesLabels(): array
    {
        return [
            self::STATUS_NEW => 'Новые задание',
            self::STATUS_INPROGRESS => 'Задания в процессе',
            self::STATUS_COMPLETE => 'Закрытые задания',
            self::STATUS_LATE => 'Просрочено',
        ];
    }
}