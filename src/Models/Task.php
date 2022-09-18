<?php

namespace TaskForce\Models;

use TaskForce\TaskActions\AbstractAction;
use TaskForce\TaskActions\ActionCancel;
use TaskForce\TaskActions\ActionComplete;
use TaskForce\TaskActions\ActionQuit;
use TaskForce\TaskActions\ActionRespond;
use TaskForce\TaskActions\ActionStart;

use TaskForce\Exceptions\NoStatusException;
use TaskForce\Exceptions\NoAvailableActionsException;
use TaskForce\Exceptions\NoMatchDataException;

class Task
{
    public const STATUS_NEW = 'new';
    public const STATUS_CANCELED = 'canceled';
    public const STATUS_INPROGRESS = 'in_progress';
    public const STATUS_DONE = 'done';
    public const STATUS_FAILED = 'failed';

    public const ACTION_START= ActionStart::class;
    public const ACTION_CANCEL = ActionCancel::class;
    public const ACTION_COMPLETE = ActionComplete::class;
    public const ACTION_RESPOND = ActionRespond::class;
    public const ACTION_QUIT = ActionQuit::class;

    private const STATUSES_PRESENTATION = [
        self::STATUS_NEW => 'Новое задание',
        self::STATUS_CANCELED => 'Задание отменено',
        self::STATUS_INPROGRESS => 'Задание в работе',
        self::STATUS_DONE => 'Задание выполнено',
        self::STATUS_FAILED => 'Задание провалено',
    ];

    private const ACTIONS_PRESENTATION = [
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

    const CUSTOMER_ACTIONS = [
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

    private $customerId;
    private $executorId;
    private $status;

    /**
     * __construct конструктор для получения новой задачи
     *
     * @param int $customerId - id заказчика
     * @param int|null $executorId - id исполнителя, по умолчанию null
     * @param string $status - Статус заказа, по умолчанию new
     *print('Передан пустой id');
            return false;
     * @return void
     */
    public function __construct(
        int $customerId,
        ?int $executorId = null,
        string $status = self::STATUS_NEW
    ) {
        if (!in_array($status, self::STATUSES)) {
            throw new NoStatusException();
        }

        $this->customerId = $customerId;
        $this->executorId = $executorId;
        $this->status = $status;
    }

    /**
     * getCustomerId - возвращает id заказчика
     *
     * @return int
     */
    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    /**
     * getExecutprId - возвращает id исполнителя
     *
     * @return ?int
     */
    public function getExecutorId(): ?int
    {
        return $this->executorId ?? print('Исполнитель не назначен');
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

    /**
     * getStatusesRu - метод для получения массива со статусами на русском языке
     *
     * @return array
     */
    public function getStatusesPresentation(): array
    {
        return self::STATUSES_PRESENTATION;
    }

    /**
     * getActionsRu - метод для получения массива с действиями на print('Передан пустой id');
            return false;русском языке
     *
     * @return array
     */
    public function getActionsPresentations(): array
    {
        return self::ACTIONS_PRESENTATION;
    }

    /**
     * getNextStatus - метод принимает действие и возвращает статус после действия или null
     *
     * @param  mixed $action - название действия
     * @return string|null
     */
    public function getNextStatus(AbstractAction $action): ?string
    {
        return self::ACTIONS_TO_STATUSES[$action::class] ?? null;
    }

    /**
     * метод принимает id пользователя, возвращает массив с возможными действиями
     *
     * @param int $user_id
     *
     * @return ?array
     */
    public function getAvailableActions(int $currentUserId): ?array
    {
        if ($currentUserId === $this->customerId) {
            $this->checkAvailableActions($this->status, self::CUSTOMER_ACTIONS);

            return $this->getObjActions(self::CUSTOMER_ACTIONS[$this->status]);
        }

        if ($currentUserId === $this->executorId) {
            $this->checkAvailableActions($this->status, self::EXECUTOR_ACTIONS);

            return $this->getObjActions(self::EXECUTOR_ACTIONS[$this->status]);
        }

        throw new NoMatchDataException();
        return null;
    }

    /**
     * метод назначает исполнителя
     *
     * @param int $userId
     *
     * @return void
     */
    public function setExecutorId(int $userId): void
    {
        if ($userId) {
            $this->executorId = $userId;
        }
    }

    /**
     * метод проверяет является ли пользователь заказчиком
     *
     * @param int $userId
     *
     * @return boolean
     */
    public function checkCustomerId(int $userId): bool
    {
        return $this->customerId && $this->customerId === $userId;
    }

    /**
     * метод проверяет является ли пользователь исполнителем
     *
     * @param int $userId
     *
     * @return boolean
     */
    public function checkExecutorId(int $userId): bool
    {
        return (bool) $this->executorId && $this->executorId === $userId;
    }

     /**
     * метод проверяет доступные действия по статусу
     *
     * @param string $status
     * @param array $actions
     *
     * @return boolean
     */
    public function checkAvailableActions(string $status, $actions): bool
    {
        if (!isset($actions[$status])) {
            throw new NoStatusException();
        }

        if (empty($actions[$status])) {
            throw new NoAvailableActionsException();
        }

        return true;
    }

    /**
     * getObjActions - метод принимает массив с названиями классов
     * и возвращает массив с экземплярами классов
     *
     * @param  array $actions
     * @return array
     */
    private function getObjActions(array $actions): array
    {
        $results = [];

        foreach($actions as $value) {
            $results[] = new $value();
        }

        return $results;
    }
}

