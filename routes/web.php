<?php

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DocumentController;
use App\Http\Middleware\Authenticate;
use App\Http\Controllers\ProfileController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [MainController::class, 'viewLogin'])->name('log');
    Route::post('/login', [LoginController::class, 'storeUser'])->name('login');
    
    Route::get('/registration', [RegisterController::class, 'viewRegister'])->name('reg');
    Route::post('/registration', [RegisterController::class, 'storeUser'])->name('storeUser');
});

Route::middleware('auth')->group(function () {
    Route::get('/', [MainController::class, 'viewIndex'])->name('index');
    Route::get('/profile', [MainController::class, 'viewProfile'])->name('profile');
    Route::get('/logout', [RegisterController::class, 'logout'])->name('logout');
    Route::post('/logout', [RegisterController::class, 'logout'])->name('logout');
});

Route::middleware('auth')->group(function () {
   Route::get('/admin', [AdminController::class, 'viewAdmin'])->name('admin');
});


Route::middleware('auth')->prefix('documents')->group(function () {
    Route::get('/', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/create', [DocumentController::class, 'create'])->name('documents.create');
    Route::post('/', [DocumentController::class, 'store'])->name('documents.store');
    Route::get('/{document}', [DocumentController::class, 'show'])->name('documents.show');
    Route::get('/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::get('/{document}/info', [DocumentController::class, 'info'])->name('documents.info');
    Route::delete('/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
});

