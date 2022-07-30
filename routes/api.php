<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::apiResource('users', UserController::class);
// Route::post("/users", [UserController::class, 'store'])->name('users.store');
// Route::get("/users", [UserController::class, 'index'])->name('users.index');
// Route::get("/users/{id}", [UserController::class, 'show'])->name('users.show');
// Route::patch("/users/{id}", [UserController::class, 'update'])->name('users.update');
// Route::delete("/users/{id}", [UserController::class, 'destroy'])->name('users.destroy');
