<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'task_id' => $faker->numberBetween(1,10),
    'mark' => $faker->numberBetween(1,5),
    'text' => $faker->paragraph(),
];

