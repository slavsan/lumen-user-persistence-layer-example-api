<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/api-specs', function () {
    $content = file_get_contents(base_path() . '/swagger.yml');
    return response($content)->header('Content-Type', 'application/x-yaml');
});

$app->group(['prefix' => '/api/v1/', 'namespace' => 'App\Http\Controllers'], function () use ($app) {
    $app->get('users/{id}', ['as' => 'fetchUser', 'uses' => 'UserController@showUser']);
    $app->get('users', 'UserController@showUsers');
    $app->post('users', 'UserController@store');
    $app->put('users/{id}', 'UserController@updateUser');
    $app->delete('users/{id}', 'UserController@deleteUser');
});
