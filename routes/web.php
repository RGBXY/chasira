<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfitController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['auth']], function () {
    Route::resource('/', TransactionController::class)
    ->middleware('permission:transactions.index|transactions.create|transactions.edit|transactions.delete');

    Route::resource('/products', ProductController::class)
    ->middleware('permission:products.index|products.create|products.edit|products.delete');

    Route::resource('/categories', CategoryController::class)
    ->middleware('permission:categories.index|categories.create|categories.edit|categories.delete');

    Route::resource('/outlets', OutletController::class)
    ->middleware('permission:outlets.index|outlets.create|outlets.edit|outlets.status');
    Route::put('/outlets/{id}/activate', [OutletController::class, 'activate']);
    Route::put('/outlets/{id}/deactivate', [OutletController::class, 'deactivate']);

    Route::get('/', [TransactionController::class, 'index'])->middleware('permission:transactions.index');
    Route::post('/transactions/addToCart', [TransactionController::class, 'cart'])->middleware('permission:transactions.index');
    Route::post('/transactions/destroyCart', [TransactionController::class, 'destroyCart'])->middleware('permission:transactions.index');

    Route::get('/sales', [SaleController::class, 'index'])->middleware('permission:sales.index');
    Route::get('/sales/filter', [SaleController::class, 'filter']);

    Route::get('/profits', [ProfitController::class, 'index'])->middleware('permission:profits.index');
    Route::get('/profits/filter', [ProfitController::class, 'filter']);

    Route::inertia('/employees', 'Employees/index')->name('employees');
    Route::inertia('/employee/add', 'Employees/CreateEdit')->name('add-employe');
    Route::inertia('/employee/edit/', 'Employees/CreateEdit')->name('edit-employe');

    Route::inertia('/dashboard', 'DashboardView')->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware('guest')->group(function () {
    Route::inertia('/register', 'Auth/RegisterView');
    Route::post('/register', [AuthController::class, 'register']);
    
    Route::inertia('/login', 'Auth/LoginView');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

