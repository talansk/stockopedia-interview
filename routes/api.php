<?php


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$router->group(['namespace' => 'V1', 'prefix' => 'v1/subscription_service','middleware' => ['api']], function() use ($router) {
    $router->post('subscriptions', 'SubscriptionController@create');
    $router->get('subscriptions', 'SubscriptionController@getList');
    $router->get('subscriptions/{id}', 'SubscriptionController@getById');
    $router->patch('subscriptions/{id}', 'SubscriptionController@update');
    $router->delete('subscriptions/{id}', 'SubscriptionController@delete');
});
