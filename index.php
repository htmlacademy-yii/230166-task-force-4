<?php

require_once 'vendor/autoload.php';

use TaskForce\Utils\Converter;

$path = 'data/categories.csv';
$table = 'category';
$file = new Converter();

$file->convertData($path, $table);

$path = 'data/cities.csv';
$table = 'city';

$file->convertData($path, $table);

