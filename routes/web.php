<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ReactionController;

/*
|--------------------------------------------------------------------------
| Web Routes - BlogSphere Modern Publication System
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('posts.index');
});

// Post Trash & Management (Placed above resource route)
Route::get('/posts-trash', [PostController::class, 'trash'])->name('posts.trash');
Route::post('/posts/{id}/restore', [PostController::class, 'restore'])->name('posts.restore');
Route::delete('/posts/{id}/force-delete', [PostController::class, 'forceDelete'])->name('posts.forceDelete');

// Reading List / Saved Bookmarks
Route::get('/reading-list', [PostController::class, 'readingList'])->name('posts.readingList');

// Re-seed Sample Data
Route::get('/create-sample-data', [PostController::class, 'createSampleData'])->name('create.sample');

// Post Resource
Route::resource('posts', PostController::class);

// Reactions (1-Click AJAX Reactions: Love, Clap, Insightful)
Route::post('/posts/{post}/react', [ReactionController::class, 'react'])->name('posts.react');

// Nested Comments
Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
Route::post('/comments/{comment}/toggle-status', [CommentController::class, 'toggleStatus'])->name('comments.toggleStatus');
