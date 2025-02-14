<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfitController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StockInController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {    
    Route::inertia('/login', 'Auth/LoginView');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

Route::group(['middleware' => ['auth']], function () {

    Route::group(['middleware' => ['inactive']], function () {
        // Transaction Route
        Route::resource('/', TransactionController::class);
        Route::post('/transactions/addToCart', [TransactionController::class, 'cart']);
        Route::post('/transactions/destroyCart', [TransactionController::class, 'destroyCart']);

        // Products Route
        Route::resource('/products', ProductController::class)
        ->middleware('permission:products.index|products.create|products.edit|products.delete');

        // Categories Route
        Route::resource('/categories', CategoryController::class)
        ->middleware('permission:categories.index|categories.create|categories.edit|categories.delete');

        // Sales Route
        Route::get('/sales', [SaleController::class, 'index'])->middleware('permission:sales.index');
        Route::get('/sales/filter', [SaleController::class, 'filter']);

        // Profits Route
        Route::get('/profits', [ProfitController::class, 'index'])->middleware('permission:profits.index');
        Route::get('/profits/filter', [ProfitController::class, 'filter']);

        // Roles Route
        Route::resource('/roles', RoleController::class)
        ->middleware('permission:roles.index|roles.create|roles.edit|roles.delete');

        Route::resource('/suppliers', SupplierController::class)
        ->middleware('permission:suppliers.index|suppliers.create|suppliers.edit|suppliers.delete');

        Route::resource('/customers', CustomersController::class)
        ->middleware('permission:customers.index|customers.create|customers.edit|customers.delete');

        Route::resource('/stock-in', StockInController::class)
        ->middleware('permission:stock_in.index|stock_in.create|stock_in.edit|stock_in.delete');

        // Employe Route
        Route::resource('/employees', EmployeeController::class)
        ->middleware('permission:employees.index|employees.create|employees.edit|employees.delete');
        Route::put('/employees/{id}/activate', [EmployeeController::class, 'activate']);
        Route::put('/employees/{id}/deactivate', [EmployeeController::class, 'deactivate']);

        Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

        
    });
    
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::inertia('/inactive', 'Inactive/index');
});

