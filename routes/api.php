<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\ProductController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Login e cadastro administradores
Route::post('/auth/register', [ApiAuthController::class, 'createUser']);
Route::post('/auth/login', [ApiAuthController::class, 'loginUser']);

//produtos
Route::get('/products/list', [ProductController::class, 'list']);
Route::get('/products/categories/list', [ProductController::class, 'list_categories']);

//produtos com usuário logado
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/products/create', [ProductController::class, 'create']);
    Route::post('/products/edit/{id_product}', [ProductController::class, 'edit']);
    Route::delete('/products/delete/{id_product}', [ProductController::class, 'delete']);
});


//Exemplo de rota com autenticação sem usar group function
//Route::apiResource('products/create', PostController::class)->middleware('auth:sanctum');



