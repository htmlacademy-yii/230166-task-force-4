<?php

require_once 'vendor/autoload.php';

use TaskForce\Models\Task;
use TaskForce\TaskActions\ActionCancel;

use TaskForce\Exceptions\NoStatusException;

$customerId = 1;
$executorId = 2;
$currentUserId = 2;
$status = 'new1';

// var_dump($cancel->getAction());
// var_dump($cancel->check($task, $currentUserId));
// var_dump($cancel->getAction());
// var_dump($task->getNextStatus($cancel));
// var_dump($task->getAvailableActions($currentUserId));



try {
    $task = new Task($customerId, $executorId, $status);
    $cancel = new Cancel();
    // var_dump($task::STATUSES);
} catch (NoStatusException $exception) {
    print $exception->getMessage();
}

$task = new Task($customerId, $executorId);
$cancel = new ActionCancel();

// $e = new StatusException();
// print var_dump($e->getMessage());

