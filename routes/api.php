<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Support\Facades\Route;

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

Route::get("ping", function () {
    return response()->json(["data" => "pong"]);
});

Route::post("session", [AuthController::class, 'login'])->name('session.login');
Route::put("session", [AuthController::class, 'refreshAccessToken'])->name('session.refresh-access-token');
Route::get("/posts", [PostController::class, 'discoverPosts'])->name('posts.discover-posts');
Route::post("/photo-urls", [PostController::class, 'requestPhotoUrl'])->middleware(['auth.token'])->name('posts.request-photo-url');
Route::post("/posts", [PostController::class, 'submitPost'])->middleware(['auth.token'])->name('posts.submit-post');
