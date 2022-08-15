<?php

namespace TaskForce\models;

class Task
{
    public const STATUS_NEW = 'new'; // новое задание
    public const STATUS_CANCELED = 'canceled'; // задание отменено
    public const STATUS_INPROGRESS = 'in_progress'; // задание в работе
    public const STATUS_DONE = 'done'; // задание готово
    public const STATUS_FAILED = 'failed'; // задание провалено

    public const ACTION_CANCEL = 'cancel'; // Отменить задание (заказчик)
    public const ACTION_RESPOND = 'respond'; // Откликнуться на задание (исполнитель)
    public const ACTION_COMPLETE = 'complete'; // Завершить задание (заказчик)
    public const ACTION_QUIT = 'quit'; // Отказаться от задания (исполнитель)
    public const ACTION_START = 'start'; // Начать задание (исполнитель)

    private const STATUSES_RU = [
        self::STATUS_NEW => 'Новая задача',
        self::STATUS_CANCELED => 'Отменено',
        self::STATUS_INPROGRESS => 'В работе',
        self::STATUS_DONE => 'Выполнено',
        self::STATUS_FAILED => 'Не выполнено'
    ];

    private const ACTIONS_RU = [
        self::ACTION_CANCEL => 'Отменить',
        self::ACTION_RESPOND => 'Откликнуться',
        self::ACTION_COMPLETE => 'Завершить',
        self::ACTION_QUIT => 'Отказаться',
        self::ACTION_START => 'Начать',
    ];

    private const STATUSES_AFTER = [
        self::ACTION_CANCEL => self::STATUS_CANCELED,
        self::ACTION_RESPOND => self::STATUS_NEW,
        self::ACTION_COMPLETE => self::STATUS_DONE,
        self::ACTION_QUIT => self::STATUS_FAILED,
        self::ACTION_START => self::STATUS_INPROGRESS,
    ];

    /**
     * __construct конструктор для получения новой задачи
     *
     * @param int $customerId - id заказчика
     * @param int|null $executorid - id исполнителя, по умолчанию null
     * @param string $status - Статус заказа, по умолчанию new
     *
     * @return void
     */
    public function __construct(
        private int $customerId,
        private int|null $executorId = null,
        private string $status = self::STATUS_NEW
    ) {}

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
    public function getNextStatus(string $action):string|null
    {
        return isset(self::STATUSES_AFTER[$action]) ? self::STATUSES_AFTER[$action] : null;
    }

    /**
     * getAvailableActions - метов принимает статус и id пользователя
     * если id равен id заказчика, то возвращает массив с возможными действиями заказчика,
     * если нет то массив с действиями исполнителя
     *
     * @param string $status - статус задачи
     * @param int $userId - id пользователя
     *
     * @return array
     */
    public function getAvailableActions(string $status, int $userId):array
    {
        $actions = [];

        if ($userId === $this->customerId) {
            switch($status) {
                case 'new':
                    $actions[] = self::ACTION_CANCEL;
                    break;
                case 'in_progress':
                    $actions[] = self::ACTION_CANCEL;
                    break;
                case 'done':
                    $actions[] = self::ACTION_COMPLETE;
                    break;
                default:
                    print('Список действий пуст');
            }

            return $actions;
        }

        switch($status) {
            case 'new':
                $actions[] = self::ACTION_RESPOND;
                break;
            case 'in_progress':
                $actions[] = self::ACTION_START;
                $actions[] = self::ACTION_QUIT;
                break;
            default:
                print('Список действий пуст');
        }

        return $actions;
    }
}

