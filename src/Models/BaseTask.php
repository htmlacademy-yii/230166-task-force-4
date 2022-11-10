<?php

namespace TaskForce\Models;

use app\models\Task;
use app\models\User;
use TaskForce\Actions\AbstractAction;
use TaskForce\Actions\ActionRefuse;
use TaskForce\Actions\ActionComplete;
use TaskForce\Actions\ActionQuit;
use TaskForce\Actions\ActionRespond;
use TaskForce\Actions\ActionStart;
use TaskForce\Exceptions\ExceptionWrongParameter;

class BaseTask
{
    public const STATUS_NEW = 'new';
    public const STATUS_CANCELED = 'canceled';
    public const STATUS_INPROGRESS = 'inprogress';
    public const STATUS_COMPLETE = 'done';
    public const STATUS_FAILED = 'failed';
    private $customerId;
    private $status;

    /**
     * @param int $customerId - id заказчика
     * @param int|null $executorId - id исполнителя, по умолчанию null
     * @param string $status - Статус заказа, по умолчанию STATUS_NEW
     * @return void
     */
    public function __construct(
        int $customerId,
        ?int $executorId = null,
        string $status = self::STATUS_NEW
    ) {
        if (!in_array($status, $this->getStatuses())) {
            throw new ExceptionWrongParameter('$status', '__construct');
        }

        $this->customerId = $customerId;
        $this->executorId = $executorId;
        $this->status = $status;
    }

    /**
     * getStatuses
     *
     * @return array
     */
    public function getStatuses(): array
    {
        return [
            self::STATUS_NEW,
            self::STATUS_CANCELED,
            self::STATUS_INPROGRESS,
            self::STATUS_COMPLETE,
            self::STATUS_FAILED
        ];
    }

    /**
     * getActions
     *
     * @return array
     */
    public static function getActions(): array
    {
        return [
            ActionStart::class,
            ActionRefuse::class,
            ActionComplete::class,
            ActionRespond::class,
            ActionQuit::class
        ];
    }

    /**
     * getStatusesLabels
     *
     * @return array
     */
    public static function getStatusesLabels(): array
    {
        return [
            self::STATUS_NEW => 'Новое задание',
            self::STATUS_CANCELED => 'Задание отменено',
            self::STATUS_INPROGRESS => 'Задание в работе',
            self::STATUS_COMPLETE => 'Задание выполнено',
            self::STATUS_FAILED => 'Задание провалено',
        ];
    }

    /**
     * getActionsLabels
     *
     * @return array
     */
    public function getActionsLabels(): array
    {
        $names = [];

        foreach($this->getActions() as $action) {
            $names[$action::NAME] = $action::LABEL;
        }

        return $names;
    }

    /**
     * getNextStatus - возвращает статус после действия
     *
     * @param  AbstractAction $action - объект класса
     * @return string|null
     */
    public function getNextStatus(AbstractAction $action): ?string
    {
        $actionsMap = [
            ActionStart::NAME => self::STATUS_INPROGRESS,
            ActionRefuse::NAME => self::STATUS_CANCELED,
            ActionComplete::NAME => self::STATUS_COMPLETE,
            ActionRespond::NAME => self::STATUS_NEW,
            ActionQuit::NAME => self::STATUS_FAILED,
        ];

        return $actionsMap[$action::NAME];
    }

    /**
     * getAvailableAction возвращает массив
     * с доступными действиями для пользователя
     *
     * @param int $userId
     *
     *
     */
    public static function getAvailableActions(Task $task, User $currentUser)
    {
        $actions = array_filter(self::getActions(), function($action) use ($task, $currentUser) {
            return $action::check($task, $currentUser);
        });

        if ($actions) {
            return array_map(function($action) {
                return $action::NAME;
            }, $actions);
        }

        return $actions;
    }

    /**
     * getCustomerId
     *
     * @return int
     */
    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    /**
     * getStatus - возвращает текущий статус
     *
     * @return ?string
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }
}

