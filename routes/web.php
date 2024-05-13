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

$router->group(['miidleware' => 'cors'], function ($router) {

});

$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');
$router->get('/profile', 'AuthController@me');
// stuff
$router->get('/stuffs', 'StuffController@index');
$router->post('/stuffs/store', 'StuffController@store');
$router->get('/stuffs/trash', 'StuffController@trash');
//User
$router->get('/users', 'UserController@index');//User
$router->post('/users/store', 'UserController@store');
$router->get('/users/trash', 'UserController@trash');
//inbound-stuff
$router->post('/inbound-stuffs/store', 'InboundStuffController@store');//inbound-stuff
$router->get('/inbound-stuffs/trash', 'InboundStuffController@trash');
//lending
$router->post('/lending/store', 'LendingController@store');
$router->get('/lending', 'LendingController@index');

// stuff
$router->patch('/stuffs/update/{id}', 'StuffController@update');
$router->delete('/stuffs/delete/{id}', 'StuffController@destroy');
$router->get('/stuffs/trash/restore/{id}', 'StuffController@restore');
$router->get('stuffs/trash/permanent-delete/{id}', 'StuffController@permanentDelete');
$router->get('/stuffs/{id}', 'StuffController@show');
//User
$router->get('/users/{id}', 'UserController@show');//User
$router->patch('/users/update/{id}', 'UserController@update');
$router->delete('/users/delete/{id}', 'UserController@destroy');
$router->get('/users/trash/restore/{id}', 'UserController@restore');
$router->get('users/trash/permanent-delete/{id}', 'UserController@permanentDelete');
//inbound-stuff
$router->get('/restore/{id}', 'InboundStuffController@restore');
$router->delete('inbound-stuffs/delete/{id}', 'InboundStuffController@destroy');
$router->delete('/inbound-stuffs/delete/{id}', 'InboundStuffController@destroy');
$router->delete('/inbound-stuffs/permanent/{id}', 'InboundStuffController@deletePermanent');
//lending
$router->delete('/lending/delete/{id}', 'LendingController@destroy');
$router->get('/lending/{id}', 'LendingController@show');
//restoration
$router->post('/lending/{lending_id}', 'RestorationController@store');
$router->post('/restorations/{lending_id}', 'RestorationController@store');
