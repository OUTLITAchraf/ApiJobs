<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    $user = $request->user()->load('roles');
    return response()->json([
        'user' => $user
    ]);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/offers/search', [OfferController::class, 'search']);
    Route::get('/offers', [OfferController::class, 'index']);
    Route::get('/offer/{offer}', [OfferController::class, 'show']);
    Route::post('/offer/{offer}/apply',[ApplicationController::class, 'store']);
    Route::get('/my-applications',[ApplicationController::class, 'myapplications']);
});
Route::middleware(['auth:sanctum', 'role:employer|admin'])->group(function () {
    Route::post('/create-offer', [OfferController::class, 'store']);
    Route::put('/update-offer/{offer}', [OfferController::class, 'update']);
    Route::delete('/delete-offer/{offer}', [OfferController::class, 'destroy']);
});

Route::middleware(['auth:sanctum', 'role:admin'])->group(function (){
    Route::get('/admin/users',[UserController::class, 'index']);
    Route::get('/admin/user/{user}',[UserController::class, 'show']);
    Route::put('/admin/update-user/{user}',[UserController::class, 'update']);
    Route::delete('/admin/delete-user/{user}',[UserController::class, 'destroy']);
});
