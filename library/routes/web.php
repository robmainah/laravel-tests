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

Route::post('/books', 'BooksController@store');
Route::patch('/books/{book}', 'BooksController@update');
Route::delete('/books/{book}', 'BooksController@destroy');

Route::get('/authors/create', 'AuthorsController@create');
Route::post('/authors', 'AuthorsController@store');

Route::post('/checkout/{book}', 'CheckoutBookController@store');
Route::post('/checkin/{book}', 'CheckInBookController@store');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
