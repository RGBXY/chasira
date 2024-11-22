<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['auth']], function () {
    Route::inertia('/', 'HomeView')->name('home');

    Route::inertia('/products', 'Product/index')->name('products');
    Route::inertia('/products/add', 'Product/CreateEdit')->name('add-products');

    Route::resource('/categories', CategoryController::class)
    ->middleware('permission:categories.index|categories.create|categories.edit|categories.delete');

    Route::inertia('/employees', 'Employees/index')->name('employees');
    Route::inertia('/employee/add', 'Employees/CreateEdit')->name('add-employe');
    Route::inertia('/employee/edit/', 'Employees/CreateEdit')->name('edit-employe');

    Route::inertia('/outlets', 'Outlets/index')->name('outlets');
    Route::inertia('/outlets/add', 'Outlets/CreateEdit')->name('add-outlets');

    Route::inertia('/dashboard', 'DashboardView')->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware('guest')->group(function () {
    Route::inertia('/register', 'Auth/RegisterView');
    Route::post('/register', [AuthController::class, 'register']);
    
    Route::inertia('/login', 'Auth/LoginView');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

