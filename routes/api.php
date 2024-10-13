<?php

use App\Http\Controllers\Authentication\AuthController;
use App\Http\Controllers\Product\CategoryController;
use App\Http\Controllers\Product\ProductController;
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

Route::apiResource('/user', UserController::class);
//route for auth
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
//middleware
Route::middleware('auth.api')->group(function (){
    Route::post('/logout', [AuthController::class, 'logout']);
    //route for crud product
    Route::get('/product', [ProductController::class, 'index']);
    Route::post('/product', [ProductController::class, 'store']);
    Route::get('/product/{id_product}', [ProductController::class, 'show']);
    Route::put('/product/{id_product}/', [ProductController::class, 'update']);
    Route::get('/product/search/{name}', [ProductController::class, 'search']);
    Route::delete('/product/{id_product}', [ProductController::class, 'destroy']);
    //route for crud category
    Route::get('/category', [CategoryController::class, 'index']);
    Route::post('/category', [CategoryController::class, 'store']);
    Route::get('/category/{id_category}', [CategoryController::class, 'show']);
    Route::put('/category/{id_category}/', [CategoryController::class, 'update']);
    Route::get('/category/search/{name}', [CategoryController::class, 'search']);
    Route::delete('/category/{id_category}', [CategoryController::class, 'destroy']);
});

