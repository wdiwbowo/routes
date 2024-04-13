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

// stuff
// struktur : $router->method('/path', 'NamaController@namaFunction');
$router->get('/stuffs', 'StuffController@index');
$router->post('/stuffs/store', 'StuffController@store');
$router->get('/stuffs/trash', 'StuffController@trash');
$router->get('/stuffs/{id}', 'StuffController@show');
$router->patch('/stuffs/update/{id}', 'StuffController@update');
$router->delete('/stuffs/delete/{id}', 'StuffController@destroy');
$router->get('/stuffs/trash/restore/{id}', 'StuffController@restore');// stuff
$router->get('stuffs/trash/permanent-delete/{id}', 'StuffController@permanentDelete');

$router->get('/users', 'UserController@index');//User
$router->post('/users/store', 'UserController@store');
$router->get('/users/trash', 'UserController@trash');
$router->get('/users/{id}', 'UserController@show');//User
$router->patch('/users/update/{id}', 'UserController@update');
$router->delete('/users/delete/{id}', 'UserController@destroy');
$router->get('/users/trash/restore/{id}', 'UserController@restore');//User
$router->get('users/trash/permanent-delete/{id}', 'UserController@permanentDelete');

$router->post('/inbound-stuffs/store', 'InboundStuffController@store');//inbound-stuff
$router->delete('inbound-stuffs/delete/{id}', 'InboundStuffController@destroy');//inbound-stuff
$router->delete('/inbound-stuffs/delete/{id}', 'InboundStuffController@destroy');
$router->delete('/inbound-stuffs/permanent/{id}', 'InboundStuffController@deletePermanent');
$router->get('/inbound-stuffs/trash', 'InboundStuffController@trash');
$router->get('/restore/{id}', 'InboundStuffController@restore');