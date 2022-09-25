<?php

require_once 'vendor/autoload.php';

use TaskForce\Utils\Converter;

$pathCategories = 'data/categories.csv';
$path = 'data/categories.sql';
$table = 'category';

$file = new Converter();

$file->convertData($pathCategories, $table);
// $outputFile = fopen($path, 'w');
// $outputFile = new SplFileObject($path, 'w');
// var_dump($outputFile);

// $outputFile->fwrite('sdfdsf');

// var_dump(explode('.', $pathCategories)[0] . '.sql');

// $file = new \SplFileObject($pathCategories);
// $file->setFlags(SplFileObject::READ_CSV);

// var_dump($file);

// foreach ($file as $row) {
//     var_dump($row);
// }
