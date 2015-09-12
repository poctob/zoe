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

Route::get('home', 'ApplicationsController@index');

Route::post('convert_file', 'ConversionController@convert');
Route::get('convert', 'ConversionController@index');
Route::get('download/{filename}', 'ConversionController@downloadFile');

Route::get('subscribe', 'SubscriptionController@index');
Route::get('subscriptions', 'SubscriptionController@show');
Route::post('subscribe', 'SubscriptionController@subscribe');
Route::post('cancelSubscription', 'SubscriptionController@cancel');

Route::get('profile', 'ProfileController@index');
Route::post('profile', 'ProfileController@update');

Route::get('applications', 'ApplicationsController@index');

Route::post('trial', 'TrialController@create');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::post('stripe/webhook', 'Laravel\Cashier\WebhookController@handleWebhook');
