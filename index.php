<?php
require_once 'vendor/autoload.php';

use TaskForce\Models\Task;

$customerId = 1;
$task = new Task($customerId);
var_dump(assert($task->getNextStatus('cancel') == Task::STATUS_CANCELED, 'canceled'));
var_dump($task->getAvailableActions($customerId));
