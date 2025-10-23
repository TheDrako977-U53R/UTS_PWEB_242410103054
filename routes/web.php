<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;

Route::get('/', fn() => redirect()->route('login'));

Route::get('/login', [PageController::class, 'login'])->name('login');
Route::post('/login', [PageController::class, 'loginProcess'])->name('login.process');
Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
Route::get('/pengelolaan', [PageController::class, 'pengelolaan'])->name('pengelolaan');
Route::post('/pengelolaan/add', [PageController::class, 'addItem'])->name('pengelolaan.add');
Route::get('/pengelolaan/{code}/edit', [PageController::class, 'editItem'])->name('pengelolaan.edit');
Route::post('/pengelolaan/{code}/update', [PageController::class, 'updateItem'])->name('pengelolaan.update');
Route::post('/pengelolaan/{code}/delete', [PageController::class, 'deleteItem'])->name('pengelolaan.delete');
Route::get('/profile', [PageController::class, 'profile'])->name('profile');
Route::get('/logout', [PageController::class, 'logout'])->name('logout');

// Temporary debug route - remove in production
Route::get('/debug/session', function () {
	return response()->json(session()->all());
});
