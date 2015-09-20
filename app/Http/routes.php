<?php

// Authentication routes...
Route::get('login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('logout', 'Auth\AuthController@getLogout');


// Registration routes...
Route::get('register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

Route::get('/', 'LinksController@index');
Route::post('/', 'LinksController@store');

Route::get('build', 'SearchController@makeElasticIndex');
Route::get('search', 'SearchController@search');

Route::get('{slug}', 'LinksController@show');
