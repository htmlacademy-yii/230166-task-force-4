<?php

require_once 'vendor/autoload.php';

use TaskForce\Models\Task;
use TaskForce\Models\Task1;


$task = new Task();
$task->foo();

$task = new Task1();
$task->foo();
