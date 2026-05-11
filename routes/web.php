<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\MasterclassController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CategoryController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/category/{category}', [CategoryController::class, 'show'])->name('category.show');

Route::middleware('auth')->group(function () {
    Route::get('/instructor/dashboard', [InstructorController::class, 'dashboard'])->name('instructor.dashboard');

    Route::middleware('role:instructor')->group(function () {
        Route::get('/masterclass/create', [MasterclassController::class, 'create'])->name('masterclass.create');
        Route::post('/masterclass', [MasterclassController::class, 'store'])->name('masterclass.store');
        Route::get('/masterclass/{masterclass}/edit', [MasterclassController::class, 'edit'])->name('masterclass.edit');
        Route::put('/masterclass/{masterclass}', [MasterclassController::class, 'update'])->name('masterclass.update');
    });

    Route::get('/registration/{masterclass}/create', [RegistrationController::class, 'create'])->name('registration.create');
    Route::post('/registration/{masterclass}', [RegistrationController::class, 'store'])->name('registration.store');
});
