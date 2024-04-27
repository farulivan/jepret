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

Route::post("session", [AuthController::class, 'login']);
Route::put("session", [AuthController::class, 'refreshAccessToken']);
Route::get("/posts", [PostController::class, 'discoverPost']);
Route::post("/photo-url", [PostController::class, 'requestPhotoUrl']);
Route::post("/posts", [PostController::class, 'storePost'])->middleware(['auth.token']);
