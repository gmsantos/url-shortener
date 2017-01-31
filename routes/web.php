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

$app->get('/urls/{id}', 'UrlController@redirectFromId');
$app->delete('/urls/{id}', 'UrlController@remove');

$app->get('/stats', 'UrlController@statistics');
$app->get('/stats/{id}', 'UrlController@view');

$app->post('/users', 'UserController@create');
$app->delete('/users/{id}', 'UserController@remove');
$app->post('/users/{userid}/urls', 'UrlController@create');
$app->get('/users/{userid}/stats', 'UrlController@statisticsByUser');
