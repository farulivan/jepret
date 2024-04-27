<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

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
Route::put("session", fn () => '');
Route::get("posts", fn () => '');
Route::post("photo-url", fn () => '');
Route::post("posts", fn () => '');
