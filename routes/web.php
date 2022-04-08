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

$router->group(['middleware' => 'auth','prefix' => 'api/v1/'], function ($router) 
{
    // endpoint private
    $router->get('profile', 'AuthController@profile');
    $router->post('transaction', 'TransactionController@transaction');
});

$router->group(['prefix' => 'api/v1/auth/'], function () use ($router) 
{
    // endpoint auth
   $router->post('register', 'AuthController@register');
   $router->post('login', 'AuthController@login');
});
// endpoint public
$router->get('api/v1/quote', 'QuoteController@read');