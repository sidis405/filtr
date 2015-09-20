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
Route::get('search', 'SearchController@searchLinks');
Route::get('search-titles-keywords', 'SearchController@searchTitlesKeywords');
Route::get('destroy', 'SearchController@destroy');

Route::get('{slug}', 'LinksController@show');
