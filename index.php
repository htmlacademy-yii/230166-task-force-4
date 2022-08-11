<?php

require_once 'src/Task.php';

$task = new Task(1);

var_dump($task->get_available_strategies('in_progress', 1));
var_dump(assert($task->get_next_status('cancel') == Task::STATUS_CANCELED, 'canceled'));
