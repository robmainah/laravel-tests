<?php

use App\Filters\Sort;
use App\Filters\PostId;
use App\Filters\UserId;
use App\Models\Comment;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

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
$filters = [
    'post_id' => PostId::class,
    'user_id' => UserId::class,
    'sort' => Sort::class,
];

Route::get('/', function () use ($filters) {
    // $comments = Comment::all();
    $comments = app(Pipeline::class)
        ->send(Comment::query())
        ->through(getClass($filters))
        ->thenReturn()
        ->get();
    // return getClass($filters);

    return view('welcome', compact('comments'));
});


function getClass($filters) {
    $neededFilters = array_keys(request()->only(array_keys($filters)));
    $allFilters = [];
    
    foreach ($neededFilters as $key => $value) {
        array_push($allFilters, $filters[$value]);
    }
    return $allFilters;
    // return [
    //     UserId::class,
    //     Sort::class,
    //     PostId::class,
    // ];
}

// Route::resource('posts', PostController::class);

Auth::routes();

Route::get('/queue', [App\Http\Controllers\UsersController::class, 'store']);//->name('home');
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', function () {
    return redirect('/posts');
})->name('home');

// Route::resource('posts', PostController::class);
Route::get('{view}', function () {
    return view('layouts.app');
})->where('view', '(.*)');
