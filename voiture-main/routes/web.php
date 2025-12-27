<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

use App\Http\Controllers\VoitureController;

Route::get('/cars', [VoitureController::class, 'index'])->name('cars.index');
Route::get('/cars/{id}', [VoitureController::class, 'show'])->name('cars.show');
Route::post('/cars/{id}/order', [VoitureController::class, 'order'])->name('cars.order');
use App\Http\Controllers\PieceController;

Route::get('/parts', [PieceController::class, 'index'])->name('parts.index');
Route::get('/parts/compatibility', [PieceController::class, 'searchCompatibility'])->name('parts.compatibility');
Route::post('/parts/{id}/buy', [PieceController::class, 'buy'])->name('parts.buy');
use App\Http\Controllers\RentalController;

Route::get('/rental', [RentalController::class, 'index'])->name('rental.index');
Route::post('/rental/{id}/book', [RentalController::class, 'book'])->name('rental.book');
use App\Http\Controllers\RevisionController;

Route::get('/revisions', [RevisionController::class, 'create'])->name('revisions.create');
Route::post('/revisions', [RevisionController::class, 'store'])->name('revisions.store');

Route::get('/login', function() { return view('auth.login'); })->name('login');
Route::get('/register', function() { return view('auth.register'); })->name('register');
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\AdminController;

Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
