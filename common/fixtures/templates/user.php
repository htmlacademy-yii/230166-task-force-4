<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'is_executor' => $faker->randomElement([0, 1]),
    'rating' => $faker->numberBetween(1,5),
    'count_feedbacks' => $faker->numberBetween(1,10),
    'email' => $faker->unique()->email(),
    'name' => $faker->firstName() ,
    'password' => $faker->password(),
    'avatar' => $faker->randomElement([
        '/img/avatars/1.png',
        '/img/avatars/2.png',
        '/img/avatars/3.png',
        '/img/avatars/4.png',
        '/img/avatars/5.png',
    ]),
    'date_of_birth' => $faker->dateTimeBetween('-100 years', '-2 years')->format('Y-m-d'),
    'phone' => substr($faker->e164PhoneNumber, 1, 11),
    'telegram' => $faker->lexify('@???????'),
    'description' => $faker->paragraph(),
    'city_id' => $faker->numberBetween(1,10)
];