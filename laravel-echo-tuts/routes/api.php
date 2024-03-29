<?php 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:web')->group(function () {
    Route::get('user', function () {
        return auth()->user()->only('id', 'name');
    });

    Route::post('posts/{post}/comments', [CommentController::class, 'store']);
});

Route::resource('posts', PostController::class);

Route::get('posts/{post}/comments', [CommentController::class, 'index']);
