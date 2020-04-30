<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', 'ListingController@indexJson');
Route::get('/video/{id}/{slug}', 'VideoController@viewJson');
Route::get('/search', 'SearchController@searchJson');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
