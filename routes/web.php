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

// Setiap Endpoint ditambahkan prefix api

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// Route Auth
$router->group(['namespace' => 'Auth'], function () use ($router) {
    $router->post('/register', 'AuthController@register');
    $router->post('/login', 'AuthController@login');
});

// Route User
$router->group(['namespace' => 'Api', 'middleware' => 'jwt.auth'], function () use ($router) {
    $router->get('/profile', 'UserController@show');
    $router->post('/update-profile', 'UserController@update');
    $router->post('/update-password', 'UserController@updatePassword');
    $router->post('/user-delete', 'UserController@destroy');
});

// Route Upload
$router->post('/single-upload', 'UploadController@singleUpload');
$router->post('/multi-upload', 'UploadController@multiUpload');
