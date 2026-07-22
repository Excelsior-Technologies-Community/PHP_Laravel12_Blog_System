<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('posts.index');
});

/*
|--------------------------------------------------------------------------
| Post CRUD
|--------------------------------------------------------------------------
*/

Route::resource('posts', PostController::class);

/*
|--------------------------------------------------------------------------
| Trash
|--------------------------------------------------------------------------
*/

Route::get('/posts-trash', [PostController::class, 'trash'])
    ->name('posts.trash');

/*
|--------------------------------------------------------------------------
| Restore
|--------------------------------------------------------------------------
*/

Route::post('/posts/{id}/restore', [PostController::class, 'restore'])
    ->name('posts.restore');

/*
|--------------------------------------------------------------------------
| Permanent Delete
|--------------------------------------------------------------------------
*/

Route::delete('/posts/{id}/force-delete', [PostController::class, 'forceDelete'])
    ->name('posts.forceDelete');
