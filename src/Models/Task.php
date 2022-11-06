<?php

namespace TaskForce\Models;

use TaskForce\Actions\AbstractAction;
use TaskForce\Actions\ActionCancel;
use TaskForce\Actions\ActionComplete;
use TaskForce\Actions\ActionQuit;
use TaskForce\Actions\ActionRespond;
use TaskForce\Actions\ActionStart;
use TaskForce\Exceptions\ExceptionRequestValueIsEmpty;
use TaskForce\Exceptions\ExceptionWrongParameter;

class Task
{
    public const STATUS_NEW = 'new';
    public const STATUS_CANCELED = 'canceled';
    public const STATUS_INPROGRESS = 'in progress';
    public const STATUS_DONE = 'done';
    public const STATUS_FAILED = 'failed';
    private $customerId;
    private $executorId;
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
            self::STATUS_DONE,
            self::STATUS_FAILED
        ];
    }

    /**
     * getActions
     *
     * @return array
     */
    public function getActions(): array
    {
        return [
            new ActionStart,
            new ActionCancel,
            new ActionComplete,
            new ActionRespond,
            new ActionQuit
        ];
    }

    /**
     * getStatusesLabels
     *
     * @return array
     */
    public function getStatusesLabels(): array
    {
        return [
            self::STATUS_NEW => 'Новое задание',
            self::STATUS_CANCELED => 'Задание отменено',
            self::STATUS_INPROGRESS => 'Задание в работе',
            self::STATUS_DONE => 'Задание выполнено',
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
            ActionCancel::NAME => self::STATUS_CANCELED,
            ActionComplete::NAME => self::STATUS_DONE,
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
     * @return ?array
     */
    public function getAvailableActions(int $userId): ?array
    {
        if ($userId !== $this->customerId && $userId !== $this->executorId) {
            throw new ExceptionWrongParameter('$userId', 'getAvailableActions');
        }

        $actions = array_filter($this->getActions(), function($value) use ($userId) {
            return $value->check($this, $userId);
        });

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

