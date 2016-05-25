<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function ($faker) {
    return [
        'email' => 'john.doe@example.com',
        'forename' => 'John',
        'surname' => 'Doe',
        'created' => '2016-05-03 10:21:50',
    ];
});