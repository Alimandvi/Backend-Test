<?php

use Illuminate\Support\Facades\Route;

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

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/','BooksController@getBooks');
Route::post('add-book','BooksController@addBook')->name('addBook');
Route::put('edit-book','BooksController@editBook')->name('editBook');
Route::delete('delete-book','BooksController@deleteBook')->name('deleteBook');