<?php
require_once 'vendor/autoload.php';

use TaskForce\Models\Task;

if (session_status()) {
    session_start();
}

$customerId = 1;
$task = new Task($customerId);
var_dump(assert($task->getNextStatus('cancel') == Task::STATUS_CANCELED, 'canceled'));
var_dump($task->getAvailableActions($customerId));

echo md5('123');


// var_dump($_SERVER);

$url = $_SERVER['REQUEST_URI'];

echo $url;

if ($url === '/add-task') {
    require 'pages/add-task.php';
} elseif ($url === '/edit-profile') {
    require 'pages/edit-profile.html';
}
