<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'name' => $faker->city(),
    'lat' => $faker->latitude($min = -90, $max = 90),
    'lng' => $faker->longitude($min = -180, $max = 180),
];