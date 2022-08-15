<?php
require_once 'vendor/autoload.php';

use TaskForce\models\Task;

$task = new Task(1);

var_dump($task->getAvailableActions('in_progress', 1));
var_dump($task->getNextStatus('cancel'));

