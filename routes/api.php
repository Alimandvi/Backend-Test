<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::group(['namespace' => 'Api'], function() {
    Route::get('books', 'BookApiController@list');
    Route::get('view-book/{id}', 'BookApiController@view');
    Route::post('create-book', 'BookApiController@create');
    Route::put('update-book/{id}',  'BookApiController@update');
    Route::delete('delete-book/{id}',  'BookApiController@delete');
});