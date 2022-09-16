<?php

require_once 'vendor/autoload.php';

use TaskForce\Models\Task;
use TaskForce\TaskActions\Cancel;

$customerId = 1;
$executorId = 2;
$currentUserId = 1;

$task = new Task($customerId, $executorId);
$cancel = new Cancel();

var_dump($cancel->getAction());
var_dump($cancel->check($task, $currentUserId));
var_dump($cancel->getAction());
var_dump($task->getNextStatus($cancel));

