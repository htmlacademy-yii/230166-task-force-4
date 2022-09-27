<?php

require_once 'vendor/autoload.php';

use TaskForce\Utils\Converter;

$path = 'data/cities.csv';
$table = 'city';
$file = new Converter();

$file->convertData($path, $table);

$path = 'data/categories.csv';
$table = 'category';

$file->convertData($path, $table);

