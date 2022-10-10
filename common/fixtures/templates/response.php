<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'task_id' => $faker->numberBetween(1,10),
    'user_id' => $faker->numberBetween(1,10),
    'is_approved' => $faker->randomElement([0, 1]),
    'message' => $faker->sentence(),
    'price'=> $faker->numberBetween(100,10000),
];

