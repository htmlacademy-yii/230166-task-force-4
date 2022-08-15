<?php
require_once 'vendor/autoload.php';

use TaskForce\models\Task;
use PHPUnit\Framework\TestCase;

$customerId = 1;
$task = new Task($customerId);
$status = 'done';

class Test extends TestCase
{
    /**
     * checkCustomerId принимает объект, проверяет равен ли статус после действия cencel в объекте,
     * и константа статуса STATUS_CENCELED в родительском классе
     *
     * @param  object $task
     * @param  int $id
     * @return boolean
     */
    public static function testNextStatus($object)
    {
        return $object->getNextStatus('cancel') == get_class($object)::STATUS_CANCELED;
    }

    /**
     * checkCustomerId принимает объект и id, проверяет ревен ли id заказчика объекта с переданным id
     *
     * @param  object $task
     * @param  int $id
     * @return boolean
     */
    public static function checkCustomerId($task, $id)
    {
        return $task->getCustomerId() === $id;
    }

    /**
     * checkStatus принимает объект и статус, проверяет ревен ли статус объекта с переданным статусом
     *
     * @param  object $task
     * @param  string $status
     * @return boolean
     */
    public static function checkStatus($task, $status)
    {
        return $task->getStatus() === $status;
    }

}

var_dump(Test::testNextStatus($task));
var_dump(Test::checkCustomerId($task, $customerId));
var_dump(Test::checkStatus($task, $status));
