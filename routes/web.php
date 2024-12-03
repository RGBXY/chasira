<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfitController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['auth']], function () {

    // Transaction Route
    Route::resource('/', TransactionController::class)
    ->middleware('permission:transactions.index|transactions.create|transactions.edit|transactions.delete');
    Route::post('/transactions/addToCart', [TransactionController::class, 'cart'])->middleware('permission:transactions.index');
    Route::post('/transactions/destroyCart', [TransactionController::class, 'destroyCart'])->middleware('permission:transactions.index');

    // Products Route
    Route::resource('/products', ProductController::class)
    ->middleware('permission:products.index|products.create|products.edit|products.delete');

    // Categories Route
    Route::resource('/categories', CategoryController::class)
    ->middleware('permission:categories.index|categories.create|categories.edit|categories.delete');

    // Outlets Route
    Route::resource('/outlets', OutletController::class)
    ->middleware('permission:outlets.index|outlets.create|outlets.edit|outlets.status');
    Route::put('/outlets/{id}/activate', [OutletController::class, 'activate']);
    Route::put('/outlets/{id}/deactivate', [OutletController::class, 'deactivate']);

    // Sales Route
    Route::get('/sales', [SaleController::class, 'index'])->middleware('permission:sales.index');
    Route::get('/sales/filter', [SaleController::class, 'filter']);

    // Profits Route
    Route::get('/profits', [ProfitController::class, 'index'])->middleware('permission:profits.index');
    Route::get('/profits/filter', [ProfitController::class, 'filter']);

    // Roles Route
    Route::resource('/roles', RoleController::class)
    ->middleware('permission:roles.index|roles.create|roles.edit|roles.delete');

    // Employe Route
    Route::resource('/employees', EmployeeController::class)
    ->middleware('permission:employees.index|employees.create|employees.edit|employees.delete');

    Route::inertia('/dashboard', 'DashboardView')->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware('guest')->group(function () {
    Route::inertia('/register', 'Auth/RegisterView');
    Route::post('/register', [AuthController::class, 'register']);
    
    Route::inertia('/login', 'Auth/LoginView');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

