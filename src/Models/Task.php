<?php

namespace TaskForce\Models;

class Task
{
    public const STATUS_NEW = 'new';
    public const STATUS_CANCELED = 'canceled';
    public const STATUS_INPROGRESS = 'in_progress';
    public const STATUS_DONE = 'done';
    public const STATUS_FAILED = 'failed';

    public const ACTION_START= 'start';
    public const ACTION_CANCEL = 'cancel';
    public const ACTION_COMPLETE = 'complete';
    public const ACTION_RESPOND = 'respond';
    public const ACTION_QUIT = 'quit';

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
        self::STATUS_INPROGRESS => [self::ACTION_COMPLETE],
        self::STATUS_DONE => [],
        self::STATUS_CANCELED => [],
        self::STATUS_FAILED => [],
    ];

    private const EXECUTOR_ACTIONS = [
        self::STATUS_NEW => [self::ACTION_RESPOND],
        self::STATUS_INPROGRESS => [self::ACTION_QUIT],
        self::STATUS_CANCELED => [],
        self::STATUS_DONE => [],
        self::STATUS_FAILED => [],
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
        return $this->executorId ?? print('Исполнитель не назначен');
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
     * метод принимает id пользователя, возвращает массив с возможными действиями
     *
     * @param int $user_id
     *
     * @return ?array
     */
    public function getAvailableActions(int $user_id):?array
    {
        if ($user_id === $this->customerId && $this->checkAvailableActions($this->status, self::CUSTOMER_ACTIONS)) {
            return self::CUSTOMER_ACTIONS[$this->status];
        }

        if ($user_id === $this->executorId && $this->checkAvailableActions($this->status, self::EXECUTOR_ACTIONS)) {
            return self::EXECUTOR_ACTIONS[$this->status];
        }

        print('С таким статусом для этого юзера ничего нет');
        return null;
    }

    /**
     * метод назначает исполнителя
     *
     * @param int $userId
     *
     * @return void
     */
    public function setExecutorId(int $userId)
    {
        if ($userId) {
            $this->executorId = $userId;
        }
    }

    /**
     * метод проверяет id заказчика
     *
     * @param int $userId
     *
     * @return boolean
     */
    public function checkCustomerId(int $userId):bool
    {
        if (!$userId) {
            print('Передан пустой id');
            return false;
        }

        if ($this->executorId !== $userId) {
            print('Переданный id не равен id заказчика');
            return false;
        }

        print('Переданный id равен id заказчика');
        return true;
    }

    /**
     * метод проверяет id исполнителя
     *
     * @param int $userId
     *
     * @return boolean
     */
    public function checkExecutorId(int $userId):bool
    {
        if (!$this->executorId) {
            print('Исполнитель не назначен');
            return false;
        }

        if (!$userId) {
            print('Передан пустой id');
            return false;
        }

        if ($this->executorId !== $userId) {
            print('Переданный id не равен id исполнителя');
            return false;
        }

        print('Переданный id равен id исполнителя');
        return true;
    }

     /**
     * метод проверяет доступные действия по статусу
     *
     * @param string $status
     * @param array $actions
     *
     * @return boolean
     */
    public function checkAvailableActions(string $status, $actions):bool
    {
        if (!isset($actions[$status])) {
            print('Нет такого статуса');
            return false;
        }

        if (empty($actions[$status])) {
            print('Список действий пуст');
            return false;
        }

        return true;
    }
}

