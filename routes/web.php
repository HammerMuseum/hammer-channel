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

Route::get('/video/{id}', 'VideoController@view');
Route::get('/viewJson/{id}', 'VideoController@viewJson');
Route::get('/', 'ListingController@index');
Route::get('/json', 'ListingController@indexJson');
Route::get('/search', 'SearchController@search');
//Route::get('/topics/{keyword}', 'ListingController@topic');
//Route::get('/search/sort/{term}/{field}', 'SearchController@sort');

//Route::get('/', function() {
//    return view('app/home');
//});

//Route::get('/search', function() {
//    return view('app/search');
//});

//Route::get('/video/{id}', function() {
//    return view('app/video');
//});
