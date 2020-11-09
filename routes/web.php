<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->group(['prefix' => 'api'], function () use ($router) {

    $router->group([
        'prefix' => 'gyms',
        'namespace' => 'Gym'
    ], function () use ($router)
    {
        $router->get('/', 'GymController@index');
        $router->get('/{id}', 'GymController@show');
        $router->post('/', 'GymController@store');
        $router->put('/{id}', 'GymController@update');
        $router->delete('/{id}', 'GymController@delete');
    });

    $router->group([
        'namespace' => 'Instructor',
        'prefix' => 'instructors'
    ], function () use ($router)
    {
        $router->get('/', 'InstructorController@index');
        $router->get('/{id}', 'InstructorController@show');
        $router->post('/', 'InstructorController@store');
        $router->put('/{id}', 'InstructorController@update');
        $router->delete('/{id}', 'InstructorController@delete');
    });

    $router->group([
        'namespace' => 'Client',
        'prefix' => 'clients'
    ], function () use ($router) {

        $router->group(['prefix' => 'emergency'], function () use ($router) {
            $router->get('/', 'EmergencyController@index');
            $router->get('/{id}', 'EmergencyController@show');
            $router->post('/', 'EmergencyController@store');
            $router->put('/{id}', 'EmergencyController@update');
            $router->delete('/{id}', 'EmergencyController@delete');
        });

        $router->group(['prefix' => 'contact'], function () use ($router) {
            $router->get('/', 'ContactController@index');
            $router->get('/{id}', 'ContactController@show');
            $router->post('/', 'ContactController@store');
            $router->put('/{id}', 'ContactController@update');
            $router->delete('/{id}', 'ContactController@delete');
        });

        $router->get('/', 'ClientController@index');
        $router->get('/{id}', 'ClientController@show');
        $router->post('/', 'ClientController@store');
        $router->put('/{id}', 'ClientController@update');
        $router->delete('/{id}', 'ClientController@delete');
        });
});
