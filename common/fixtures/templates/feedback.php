<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'task_id' => $faker->numberBetween(1,10),
    'customer_id' => $faker->numberBetween(1,10),
    'executor_id' => $faker->numberBetween(1,10),
    'rating' => $faker->numberBetween(1,5),
    'message' => $faker->paragraph(),
];

