<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('login', [AuthController::class, 'login'])->name('auth.login');

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('me', [AuthController::class, 'me'])->name('auth.me');
        Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout');
    });
});

//Route::group([
//    'prefix' => 'board',
//    'middleware' => ['auth:sanctum'],
//    'controller' => BoardController::class
//], function(){
//    Route::get('index');
//});

Route::prefix('board')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [BoardController::class, 'index'])->name('board.index');
    Route::post('/', [BoardController::class, 'store'])->name('board.store');
    Route::get('/{board}', [BoardController::class, 'show'])->name('board.show');
    Route::patch('/{board}', [BoardController::class, 'update'])->name('board.update');
    Route::delete('/{board}', [BoardController::class, 'destroy'])->name('board.destroy');

    Route::post('{board}/comment', [CommentController::class, 'store'])->name('comment.store');
    Route::patch('/comment/{comment}', [CommentController::class, 'update'])->name('comment.update');
    Route::delete('/comment/{comment}', [CommentController::class, 'destroy'])->name('comment.destroy');
});
