<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\JenisController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SubController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/register', RegisterController::class)->name('register');
Route::post('/login', LoginController::class)->name('login');
Route::middleware('auth:api')->post('/logout', LogoutController::class)->name('logout');
Route::middleware('auth:api')->get('/user/{id}', [UserController::class, 'show']);


Route::middleware('auth:api')->group(function(){
    //midtrans
    Route::post('/subcribe/{id}',[SubscriptionController::class, 'subscribe']);
    //untuk jenis
    Route::get('/jenis/{id}',[JenisController::class, 'show']);
    Route::put('/jenis/update/{id}',[JenisController::class, 'update']);
    Route::delete('/jenis/delete/{id}',[JenisController::class, 'delete']);
    //untuk movies
    Route::put('/movies/update/{id}', [MovieController::class, 'update']);
    Route::delete('/movies/delete/{id}', [MovieController::class, 'destroy']);
    //halaman subcribe
    Route::put('/sub/update/{id}',[SubController::class, 'update']);
    Route::get('/sub/delete',[SubController::class, 'destroy']);
});
Route::post('/jenis/add',[JenisController::class, 'store']);
Route::post('/sub/add',[SubController::class, 'store']);
Route::post('/movies/add',[MovieController::class, 'store']);

Route::get('/jenis', [JenisController::class, 'index']);
Route::get('/movies', [MovieController::class, 'index']);
Route::get('/movies/show/{id}', [MovieController::class, 'show']);
Route::get('/movie/genre/{id}', [MovieController::class, 'genre']);
Route::get('/sub',[SubController::class, 'index']);
Route::get('/search/{key}', [MovieController::class, 'search']);
Route::post('/midtrans/notification',[SubscriptionController::class, 'handleNotification']);




