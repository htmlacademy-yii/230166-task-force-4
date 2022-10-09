<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'customer_id' => $faker->numberBetween(1,10),
    'executor_id' => $faker->numberBetween(1,10),
    'category_id' => $faker->numberBetween(1,10),
    'status' => $faker->randomElement(['new', 'cencelled', 'in_progress', 'done', 'failed']),
    'title' => $faker->sentence(),
    'text' => $faker->paragraph(),
    'price' => $faker->numberBetween(100,10000),
    'deadline' => $faker->dateTimeBetween('now', '+15 days')->format('Y-m-d')
];
