<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'task_id' => $faker->numberBetween(1,10),
    'user_id' => $faker->numberBetween(1,10),
    'url' => $faker->lexify('???????.txt'),
];