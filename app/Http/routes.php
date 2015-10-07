<?php
// Authentication routes...
Route::get('login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('logout', 'Auth\AuthController@getLogout');
// Registration routes...
Route::get('register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

Route::get('images/{path}', function (League\Glide\Server $server, Illuminate\Http\Request $request) {
    $server->outputImage($request);
})->where('path', '.*');

Route::get('/', 'LinksController@index');
Route::post('/', 'LinksController@store');
Route::get('bulk-seed', 'LinksController@seed');
Route::get('parse', 'LinksController@parse');
Route::get('status', 'SystemController@index');

Route::get('entities/bulk-screenshots', 'EntitiesController@updateScreenshots');
Route::get('entities/{slug}', 'EntitiesController@show');

Route::get('keywords/{q}', function($q){
    return redirect()->to('/search?q=' . urlencode(str_replace('-', ' ', $q)));
});

Route::get('build', 'SearchController@makeElasticIndex');
Route::get('search', 'SearchController@searchLinks');
Route::get('search-titles-keywords', 'SearchController@searchTitlesKeywords');
Route::get('destroy', 'SearchController@destroy');

Route::get('/users', 'UserController@index');
Route::get('/users/{id}', 'UserController@show');

Route::post('/user/{id}/entities', 'UserController@addUserEntities');
Route::delete('/user/{id}/entities', 'UserController@deleteUserEntities');

Route::get('/load/{slug}', 'LinksController@showAjax');
Route::get('{slug}', 'LinksController@show');


