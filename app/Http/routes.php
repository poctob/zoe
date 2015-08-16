<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

/*Route::post('convert_file', [
    'middleware' => 'auth',
    'uses' =>'ConversionController@convert'
]);

Route::get('convert', [
    'middleware' => 'auth',
    'uses' =>'ConversionController@index'
]);

Route::get('download/{filename}', [
    'middleware' => 'auth',
    'uses' =>'ConversionController@downloadFile'
]);*/

Route::post('convert_file', 'ConversionController@convert');
Route::get('convert', 'ConversionController@index',['middleware' => 'auth']);
Route::get('download/{filename}', 'ConversionController@downloadFile');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
