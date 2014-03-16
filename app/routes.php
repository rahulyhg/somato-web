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
|
*/

// Create a default card for a user
Route::post('card/create/default/{user_id}', array('uses' => 'CardController@makeDefaultCard'));

// Get the user's current card
Route::get('card/get/current/{user_id}', array('uses' => 'CardController@getCurrent'));

// Get a card by id
Route::get('card/get/{card_id}', array('uses' => 'CardController@get'));

/*
|--------------------------------------------------------------------------
| Templates
|--------------------------------------------------------------------------
|
| Template creator web app
|
*/

// Template creator JS app (new or resume editing)
Route::get('template/creator/{id?}', array('uses' => 'TemplateController@creator'));

// Save a template from the creator
Route::post('template/save', array('uses' => 'TemplateController@save'));