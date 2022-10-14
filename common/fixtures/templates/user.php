<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'is_executor' => $faker->randomElement([0, 1]),
    'raiting' => $faker->numberBetween(1,5),
    'email' => $faker->unique()->email(),
    'name' => $faker->name() ,
    'password' => $faker->password(),
    'avatar' => $faker->randomElement([
        '@app/web/img/avatars/1.png',
        '@app/web/img/avatars/2.png',
        '@app/web/img/avatars/3.png',
        '@app/web/img/avatars/4.png',
        '@app/web/img/avatars/5.png',
    ]),
    'date_of_birth' => $faker->dateTimeBetween('-100 years', '-2 years')->format('Y-m-d'),
    'phone' => substr($faker->e164PhoneNumber, 1, 11),
    'telegram' => $faker->lexify('@???????'),
    'city_id' => $faker->numberBetween(1,10)
];