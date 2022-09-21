<?php

require_once 'vendor/autoload.php';

use TaskForce\Models\Task;
use TaskForce\Exceptions\ExceptionRequestValueIsEmpty;
use TaskForce\Exceptions\ExceptionWrongParameter;
use TaskForce\TaskActions\ActionQuit;

$customerId = 1;
$executorId = 2;
$currentUserId = 2;
$status = 'new';
$wrongStatus = 'new1';

try {
    $task = new Task($customerId, $executorId, $status);
    // $task2 = new Task($customerId, $executorId, $wrongStatus);
    $quit = new ActionQuit;

    var_dump($task->getAvailableActions($executorId));
    var_dump($task->getAvailableActions($customerId));
    // var_dump($task->getAvailableActions(3));
    var_dump($quit::NAME);
    var_dump($task->getNextStatus(new ActionQuit));
    var_dump($task->getStatusesExternalNames());
    var_dump($task->getStatuses());
    var_dump($task->getActions());
    var_dump($task->getStatus());
} catch (ExceptionRequestValueIsEmpty $exception) {
    print $exception->getMessage();
} catch (ExceptionWrongParameter $exception) {
    print $exception->getMessage();
}
