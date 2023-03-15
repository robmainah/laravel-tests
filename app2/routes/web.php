<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\CheckoutController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/login', function () {
    return view('welcome');
})->name('login');

Route::post('books', [BookController::class, 'store']);
Route::patch('books/{book}', [BookController::class, 'update']);
Route::delete('books/{book}', [BookController::class, 'delete']);

Route::post('authors', [AuthorController::class, 'store']);
Route::post('checkout/{book}', [CheckoutController::class, 'store']);
Route::post('checkin/{book}', CheckinController::class);
