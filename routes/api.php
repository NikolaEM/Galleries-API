<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\GalleryController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth');
Route::post('refresh', [AuthController::class, 'refreshToken']);
Route::get('my-profile', [AuthController::class, 'getMyProfile'])->middleware('auth');

Route::get('/galleries', [GalleryController::class, 'index']);
Route::get('/galleries/{id}', [GalleryController::class, 'show']);
Route::post('/galleries', [GalleryController::class, 'store'])->middleware('auth');
Route::put('/galleries/{id}', [GalleryController::class, 'update'])->middleware('auth');
Route::delete('/galleries/{id}', [GalleryController::class, 'delete'])->middleware('auth');

Route::post('/galleries/{id}/comments', [CommentController::class, 'store'])->middleware('auth');
Route::delete('/comments/{id}', [CommentController::class, 'delete'])->middleware('auth');


