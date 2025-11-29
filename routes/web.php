<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DocumentController;
use APp\Http\Controllers\CounterpartyController;


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

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'viewAdmin'])->name('admin');
    
    Route::get('/users/{id}', [AdminController::class, 'viewUser'])->name('admin.user.view');
    Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.user.edit');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('admin.user.update');
    Route::post('/users/{id}/verify', [AdminController::class, 'verifyUser'])->name('admin.user.verify');
    Route::post('/users/{id}/toggle-block', [AdminController::class, 'toggleBlockUser'])->name('admin.user.toggle-block');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.user.delete');
    Route::put('/users/{id}/password', [AdminController::class, 'updatePassword'])->name('admin.user.update-password');
});

Route::prefix('admin/counterparties')->name('admin.counterparties.')->group(function () {
    Route::get('/', [App\Http\Controllers\CounterpartyController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\CounterpartyController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\CounterpartyController::class, 'store'])->name('store');
    Route::get('/{counterparty}/edit', [App\Http\Controllers\CounterpartyController::class, 'edit'])->name('edit');
    Route::put('/{counterparty}', [App\Http\Controllers\CounterpartyController::class, 'update'])->name('update');
    Route::delete('/{counterparty}', [App\Http\Controllers\CounterpartyController::class, 'destroy'])->name('destroy');
});

Route::get('/admin/statistics', [App\Http\Controllers\AdminController::class, 'statistics'])->name('admin.statistics');

Route::middleware('auth')->prefix('documents')->group(function () {
    Route::get('/', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/create', [DocumentController::class, 'create'])->name('documents.create');
    Route::post('/', [DocumentController::class, 'store'])->name('documents.store');
    Route::get('/{document}', [DocumentController::class, 'show'])->name('documents.show');
    Route::get('/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::get('/{document}/info', [DocumentController::class, 'info'])->name('documents.info');
    Route::delete('/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
});

