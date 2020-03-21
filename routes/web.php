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

$router->get('/', function () {
    return 'Welcome!';
});


$router->get('/urls/{id}', 'UrlController@redirectFromId');
$router->delete('/urls/{id}', 'UrlController@remove');

$router->get('/stats', 'UrlController@statistics');
$router->get('/stats/{id}', 'UrlController@view');

$router->post('/users', 'UserController@create');
$router->delete('/users/{id}', 'UserController@remove');
$router->post('/users/{userid}/urls', 'UrlController@create');
$router->get('/users/{userid}/stats', 'UrlController@statisticsByUser');
