<?php

namespace TaskForce\models;

class Task
{
    public const STATUS_NEW = 'new';
    public const STATUS_CANCELED = 'canceled';
    public const STATUS_INPROGRESS = 'in_progress';
    public const STATUS_DONE = 'done';
    public const STATUS_FAILED = 'failed';

    public const ACTION_START= 'start'; // заказчик
    public const ACTION_CANCEL = 'cancel'; // заказчик
    public const ACTION_COMPLETE = 'complete'; // заказчик
    public const ACTION_RESPOND = 'respond'; // исполнитель
    public const ACTION_QUIT = 'quit'; // исполнитель

    private const STATUSES_RU = [
        self::STATUS_NEW => 'Новое задание',
        self::STATUS_CANCELED => 'Задание отменено',
        self::STATUS_INPROGRESS => 'Задание в работе',
        self::STATUS_DONE => 'Задание выполнено',
        self::STATUS_FAILED => 'Задание провалено',
    ];

    private const ACTIONS_RU = [
        self::ACTION_START => 'Запуск задания',
        self::ACTION_CANCEL => 'Отменить задание',
        self::ACTION_COMPLETE => 'Завершить задание',
        self::ACTION_RESPOND => 'Откликнуться на задание',
        self::ACTION_QUIT => 'Отказаться от задания',
    ];

    private const ACTIONS_TO_STATUSES = [
        self::ACTION_START => self::STATUS_INPROGRESS,
        self::ACTION_CANCEL => self::STATUS_CANCELED,
        self::ACTION_COMPLETE => self::STATUS_DONE,
        self::ACTION_RESPOND => self::STATUS_NEW,
        self::ACTION_QUIT => self::STATUS_FAILED,
    ];

    private const CUSTOMER_ACTIONS = [
        self::STATUS_NEW => [self::ACTION_CANCEL, self::ACTION_START],
        self::STATUS_DONE => [self::ACTION_COMPLETE],
        self::STATUS_INPROGRESS => null,
        self::STATUS_CANCELED => null,
        self::STATUS_FAILED => null,
    ];

    private const EXECUTOR_ACTIONS = [
        self::STATUS_NEW => [self::ACTION_RESPOND],
        self::STATUS_INPROGRESS => [self::ACTION_QUIT],
        self::STATUS_CANCELED => null,
        self::STATUS_DONE => null,
        self::STATUS_FAILED => null,
    ];

    /**
     * __construct конструктор для получения новой задачи
     *
     * @param int $customerId - id заказчика
     * @param int|null $executorId - id исполнителя, по умолчанию null
     * @param string $status - Статус заказа, по умолчанию new
     *
     * @return void
     */
    public function __construct(
        private int $customerId,
        private ?int $executorId = null,
        private string $status = self::STATUS_NEW
    ) {}

    /**
     * getCustomerId - возвращает id заказчика
     *
     * @return int
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * getExecutprId - возвращает id исполнителя
     *
     * @return ?int
     */
    public function getExecutorId()
    {
        return $this->executorId;
    }

    /**
     * getStatus - возвращает текущий статус
     *
     * @return ?string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * getStatusesRu - метод для получения массива со статусами на русском языке
     *
     * @return array
     */
    public function getStatusesRu():array
    {
        return self::STATUSES_RU;
    }

    /**
     * getActionsRu - метод для получения массива с действиями на русском языке
     *
     * @return array
     */
    public function getActionsRu():array
    {
        return self::ACTIONS_RU;
    }

    /**
     * getNextStatus - метод принимает действие и возвращает статус после действия или null
     *
     * @param  mixed $action - название действия
     * @return string|null
     */
    public function getNextStatus(string $action):?string
    {
        return self::ACTIONS_TO_STATUSES[$action] ?? null;
    }

    /**
     * getAvailableActions - метов принимает статус и id пользователя
     * если $userId равен id заказчика, то возвращает массив с возможными действиями заказчика,
     * если $userId равен id исполнителя, то массив с действиями исполнителя
     * по умолчанию возвращает null
     *
     * @param int $userId - id получателя
     *
     * @return ?array
     */
    public function getAvailableActions(int $userId):?array
    {
        if ($userId === $this->customerId) {
            return self::CUSTOMER_ACTIONS;
        }

        if ($userId === $this->executorId) {
            return self::EXECUTOR_ACTIONS;
        }

        return null;
    }
}

