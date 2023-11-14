<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\TransactionController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::controller(AuthController::class)->group(function () {
    Route::post("login", "login");
    Route::post("register", "register");
    Route::post("logout", "logout");
    Route::post("refresh", "refresh");
});

Route::controller(ProductController::class)->group(function () {
    Route::post("product", "index");
    Route::post("product/insert", "store");
    Route::post("product/detail/{id}", "show");
    Route::post("product/update/{id}", "update");
    Route::post("product/delete/{id}", "destroy");
});

Route::controller(TransactionController::class)->group(function () { 
    Route::post("transaction","index");
    Route::post("transaction/insert","store");
});
