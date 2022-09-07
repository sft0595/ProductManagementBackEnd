<?php

use App\Http\Controllers\AttributeController;
use App\Http\Controllers\AttrvalueController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Models\Attrvalue;

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


Route::post("login", [AuthController::class, 'login']);
Route::post("register", [AuthController::class, 'register']);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('attributes', AttributeController::class);
Route::apiResource('attrvalues', AttrvalueController::class);

Route::group(['middleware' => 'auth:api'], function () {
   Route::get('user', [UserController::class, 'currentUser']);
   Route::post('user/info', [UserController::class, 'updateInfo']);
   Route::post('user/password', [UserController::class, 'updatePassword']);

   Route::apiResource('users', UserController::class);
   Route::apiResource('roles', RoleController::class);
});



// Route::post("/users", [UserController::class, 'store'])->name('users.store');
// Route::get("/users", [UserController::class, 'index'])->name('users.index');
// Route::get("/users/{id}", [UserController::class, 'show'])->name('users.show');
// Route::patch("/users/{id}", [UserController::class, 'update'])->name('users.update');
// Route::delete("/users/{id}", [UserController::class, 'destroy'])->name('users.destroy');
