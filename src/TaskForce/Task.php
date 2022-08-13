<?php

namespace TaskForce;

class Task
{
    public const STATUS_NEW = 'new'; // новое задание
    public const STATUS_CANCELED = 'canceled'; // задание отменено
    public const STATUS_INPROGRESS = 'in_progress'; // задание в работе
    public const STATUS_DONE = 'done'; // задание готово
    public const STATUS_FAILED = 'failed'; // задание провалено

    public const STRATEGY_CANCEL = 'cancel'; // Отменить задание (заказчик)
    public const STRATEGY_RESPOND = 'respond'; // Откликнуться на задание (исполнитель)
    public const STRATEGY_COMPLETE = 'complete'; // Завершить задание (заказчик)
    public const STRATEGY_QUIT = 'quit'; // Отказаться от задания (исполнитель)
    public const STRATEGY_START = 'start'; // Начать задание (исполнитель)
    public const STRATEGY_RESTORE = 'restore'; // Восстановить отмененное задание (заказчик)

    private const STATUSES_RU = [
        self::STATUS_NEW => 'Новая задача',
        self::STATUS_CANCELED => 'Отменено',
        self::STATUS_INPROGRESS => 'В работе',
        self::STATUS_DONE => 'Выполнено',
        self::STATUS_FAILED => 'Не выполнено'
    ];

    private const STRATEGIES_RU = [
        self::STRATEGY_CANCEL => 'Отменить',
        self::STRATEGY_RESPOND => 'Откликнуться',
        self::STRATEGY_COMPLETE => 'Завершить',
        self::STRATEGY_QUIT => 'Отказаться',
        self::STRATEGY_START => 'Начать',
        self::STRATEGY_RESTORE => 'Восстановить'
    ];

    private const STATUSES_AFTER = [
        self::STRATEGY_CANCEL => self::STATUS_CANCELED,
        self::STRATEGY_RESPOND => self::STATUS_NEW,
        self::STRATEGY_COMPLETE => self::STATUS_DONE,
        self::STRATEGY_QUIT => self::STATUS_NEW,
        self::STRATEGY_START => self::STATUS_INPROGRESS,
        self::STRATEGY_RESTORE => self::STATUS_NEW
    ];

    /**
     * __construct конструктор для получения новой задачи
     *
     * @param int $user_id - id заказчика
     * @param int|null $executor_id - id исполнителя, по умолчанию null
     * @param string $status - Статус заказа, по умолчанию new
     *
     * @return void
     */
    public function __construct(
        private int $user_id,
        private int|null $executor_id = null,
        private string $status = self::STATUS_NEW
    ) {}

    /**
     * get_statuses_ru - метод для получения массива со статусами на русском языке
     *
     * @return array
     */
    public function get_statuses_ru():array
    {
        return self::STATUSES_RU;
    }

    /**
     * get_strategies_ru - метод для получения массива с действиями на русском языке
     *
     * @return array
     */
    public function get_strategies_ru():array
    {
        return self::STRATEGIES_RU;
    }

    /**
     * get_status - метод принимает действие и возвращает статус после действия или null
     *
     * @param  mixed $strategy - название действия
     * @return string|null
     */
    public function get_next_status(string $strategy):string|null
    {
        return isset(self::STATUSES_AFTER[$strategy]) ? self::STATUSES_AFTER[$strategy] : null;
    }

    /**
     * get_available_strategies - метов принимает статус и id пользователя
     * если id равен id заказчика, то возвращает массив с возможными действиями заказчика,
     * если нет то массив с действиями исполнителя
     *
     * @param string $status - статус задачи
     * @param int $user_id - id пользователя
     *
     * @return array
     */
    public function get_available_strategies(string $status, int $user_id):array|null
    {
        $strategies = [];

        if ($user_id === $this->user_id) {
            switch($status) {
                case 'new':
                    $strategies[] = self::STRATEGY_CANCEL;
                    break;
                case 'canceled':
                    $strategies[] = self::STRATEGY_RESTORE;
                    break;
                case 'in_progress':
                    $strategies[] = self::STRATEGY_CANCEL;
                    break;
                case 'done':
                    $strategies[] = self::STRATEGY_COMPLETE;
                    break;
                case 'filed':
                    $strategies[] = self::STRATEGY_RESTORE;
                    break;
                default:
                    print('Такой статус недоступен для заказчика');
            }

            return $strategies;
        }

        switch($status) {
            case 'new':
                $strategies[] = self::STRATEGY_RESPOND;
                break;
            case 'in_progress':
                $strategies[] = self::STRATEGY_START;
                $strategies[] = self::STRATEGY_QUIT;
                break;
            default:
                print('Такой статус недоступен для исполнителя');
        }

        return $strategies;
    }
}

