<?php

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

Route::post("/users/login", [\App\Http\Controllers\UserController::class, "login"])
->name("login");
Route::get("/users/current", [\App\Http\Controllers\UserController::class, "current"])
    ->middleware(["auth"]);

Route::get("/api/users/current", [\App\Http\Controllers\UserController::class, "current"])
    ->middleware(["auth:token"]);
