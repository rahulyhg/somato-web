<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});

/*
|--------------------------------------------------------------------------
| Users
|--------------------------------------------------------------------------
|
| User related routes for API
|
*/

// Register
Route::post('user/register', array('uses' => 'UserController@register'));

// Login
Route::post('user/login', array('uses' => 'UserController@login'));

// Logout
Route::post('user/logout', array('uses' => 'UserController@logout'));

// Update
Route::post('user/update', array('uses' => 'UserController@update'));

// Create a LinkedIn user
Route::post('user/linkedin', array('uses' => 'UserController@linkedin'));

// Get user
Route::get('user/{id}', array('uses' => 'UserController@getUser'));

/*
|--------------------------------------------------------------------------
| Cards
|--------------------------------------------------------------------------
|
| Business card related routes
| Includes theme creator
|
*/

// Create a default card for a user
Route::post('card/create/default/{id}', array('uses' => 'CardController@makeDefaultCard'));