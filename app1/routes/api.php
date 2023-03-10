<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoListController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\ServiceController;

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

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('todo-lists', TodoListController::class);
    Route::apiResource('todo-lists.tasks', TaskController::class)
    ->except(['show'])
    ->shallow();

    Route::apiResource('labels', LabelController::class);
    Route::get('service/connect/{service}', [ServiceController::class, 'connect'])->name('service.connect');
    Route::post('service/callback', [ServiceController::class, 'callback'])->name('service.callback');
    Route::post('service/{service}', [ServiceController::class, 'store'])->name('service.store');
});

Route::post('register', RegisterController::class)->name('user.register');
Route::post('login', LoginController::class)->name('user.login');
// Route::get('task', [TaskController::class, 'index'])->name('task.index');
// Route::post('task', [TaskController::class, 'store'])->name('task.store');
// Route::delete('task/{task}', [TaskController::class, 'destroy'])->name('task.destroy');
// Route::get('todo-list/{list}', [TodoListController::class, 'show'])->name('todo-list.show');
// Route::post('todo-list', [TodoListController::class, 'store'])->name('todo-list.store');
// Route::post('todo-list/{list}', [TodoListController::class, 'update'])->name('todo-list.update');
// Route::delete('todo-list/{list}', 'App\Http\Controllers\TodoListController@destroy', )->name('todo-list.destroy');
