<?php

require_once 'vendor/autoload.php';

use TaskForce\Models\Task;
use TaskForce\TaskActions\ActionCancel;

$customerId = 1;
$executorId = 2;
$currentUserId = 2;

$task = new Task($customerId, $executorId);
$cancel = new ActionCancel();

var_dump($cancel->getAction());
var_dump($cancel->check($task, $currentUserId));
var_dump($cancel->getAction());
var_dump($task->getNextStatus($cancel));
var_dump($task->getAvailableActions($currentUserId));

