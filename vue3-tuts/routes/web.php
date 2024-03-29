<?php


use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Route;

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

// Route::get('admin', function () {
//     return view('admin.dashboard');
// });

Route::get('csrf', function () {
    return csrf_token();
});

// Route::get('{view}', AppController::class);
Route::get('{view}', AppController::class)->where('view', '(.*)');

