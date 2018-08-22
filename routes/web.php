<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/update/cities', 'UpdateCitiesController@txtToArray');

Route::get('/autocomplete', 'SearchFormController@index');

Route::get('/autocomplete/search', [
    'as' => 'autocomplete.search',
    'uses' => 'SearchFormController@autocompleteSearch'
]);

Route::get('map', 'SearchFormController@map');

