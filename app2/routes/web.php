<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;
 
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

Route::get('/', function () {
    return view('welcome');
});

Route::post('books', [BookController::class, 'store']);
Route::patch('books/{book}', [BookController::class, 'update']);
Route::delete('books/{book}', [BookController::class, 'delete']);

Route::post('authors', [AuthorController::class, 'store']);
Route::patch('books/{book}', [BookController::class, 'update']);
Route::delete('books/{book}', [BookController::class, 'delete']);
