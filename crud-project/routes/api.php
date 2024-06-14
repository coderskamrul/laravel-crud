<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserCrudController;
use App\Http\Controllers\UpdateUserController;
use App\Http\Controllers\CreateUserController;
use App\Http\Controllers\DeleteUserController;
use App\Http\Controllers\FindUserController;
/*
|--------------------------------------------------------------------------
| API Routes 
|--------------------------------------------------------------------------
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::GET('/users', [UserCrudController::class, 'index']);
Route::GET('/user-find/{id}', [FindUserController::class, 'show']);
Route::PUT('/user-edit/{id}', [UpdateUserController::class, 'update']);
Route::POST('/user-create', [CreateUserController::class, 'store']);
Route::DELETE('/user-delete/{id}', [DeleteUserController::class, 'destroy']); 