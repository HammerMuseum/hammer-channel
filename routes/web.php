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

Route::get('/', 'ListingController@index');
Route::get('/video/{slug}', 'VideoController@view');
Route::get('/search', 'SearchController@search');
//Route::get('/topics/{keyword}', 'ListingController@topic');
//Route::get('/search/sort/{term}/{field}', 'SearchController@sort');
