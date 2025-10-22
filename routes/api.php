<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

//----Auth Routes----
// POST   /register
// POST   /login
// POST   /logout

//----Post Routes----
// POST   /posts                   → create new post
// GET    /posts                   → show all posts
// GET    /posts?search=keyword    → search posts
// GET    /posts/{id}              → show one post
// GET    /users/{id}/posts        → show posts for specific user
// PUT    /posts/{id}              → update post
// DELETE /posts/{id}              → delete post



//----Comment Routes----
// POST   /posts/{id}/comments     → create a new comment
// GET    /posts/{id}/comments     → show comments for a specific post
// PUT    /comments/{id}           → update comment
// DELETE /comments/{id}           → delete comment



//----User Routes----
// GET    /users            → show all users
// GET    /users/{id}       → show specific user
// PUT    /users/{id}       → update user
// DELETE /users/{id}       → delete user


//------------------- Public Routes -------------------//

// Auth routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Post routes
// show all with ?page=x or search with ?search=
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{id}', [PostController::class, 'show']);

// Comments routes
Route::get('/posts/{id}/comments', [CommentController::class, 'index']);


//------------------- Protected Routes -------------------//

Route::middleware('auth:sanctum')->group(function () {

    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);

    // Posts routes
    Route::post('/posts', [PostController::class, 'store']);
    Route::put('/posts/{id}', [PostController::class, 'update']);
    Route::delete('/posts/{id}', [PostController::class, 'destroy']);

    // Comments routes
    Route::post('/posts/{id}/comments', [CommentController::class, 'store']);
    Route::put('/comments/{id}', [CommentController::class, 'update']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);
});
