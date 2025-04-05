<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfitController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StockInController;
use App\Http\Controllers\StockOpnameController;
use App\Http\Controllers\StockOutController;
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
        Route::post('/transactions/searchByBarcode', [TransactionController::class, 'searchByBarcode']);
        Route::post('/transactions/destroyCart', [TransactionController::class, 'destroyCart']);
        Route::get('/transactions/printReceipt', [TransactionController::class, 'printReceipt']);

        // Products Route
        Route::resource('/products', ProductController::class)
        ->middleware('permission:products.index|products.create|products.edit|products.delete');
        Route::post('/products/searchProductName', [ProductController::class, 'searchProductName']);
        Route::post('/categories/dropDownCategory', [CategoryController::class, 'dropDownCategory']);

        // Categories Route
        Route::resource('/categories', CategoryController::class)
        ->middleware('permission:categories.index|categories.create|categories.edit|categories.delete');
        Route::post('/categories/searchCategoryName', [CategoryController::class, 'searchCategoryName']);
        Route::post('/categories/dropDownCategory', [CategoryController::class, 'dropDownCategory']);

        // Sales Route
        Route::get('/sales', [SaleController::class, 'index'])->middleware('permission:sales.index');
        Route::post('/sales/filterDate', [SaleController::class, 'filterDate']);
        Route::post('/sales/transaction_detail_filter', [SaleController::class, 'transaction_detail_filter']);

        // Profits Route
        Route::get('/profits', [ProfitController::class, 'index'])->middleware('permission:profits.index');
        Route::post('/profits/filterDate', [ProfitController::class, 'filterDate']);

        // Roles Route
        Route::resource('/roles', RoleController::class)
        ->middleware('permission:roles.index|roles.create|roles.edit|roles.delete');
        Route::post('/roles/searchRoles', [RoleController::class, 'searchRoles'])->name('roles.searchRoles');
        Route::post('/roles/dropDownRole', [RoleController::class, 'dropDownRole']);

        // Supplier Route
        Route::resource('/suppliers', SupplierController::class)
        ->middleware('permission:suppliers.index|suppliers.create|suppliers.edit|suppliers.delete');
        Route::post('/suppliers/searchSupplierName', [SupplierController::class, 'searchSupplierName']);
        
        // Customer Route
        Route::resource('/customers', CustomersController::class)
        ->middleware('permission:customers.index|customers.create|customers.edit|customers.delete');
        Route::post('/customers/searchCustomerName', [CustomersController::class, 'searchCustomerName']);
        
        // Stock-in Route
        Route::resource('/stock-in', StockInController::class)
        ->middleware('permission:stock_in.index|stock_in.create|stock_in.edit|stock_in.delete')->except(['show']);
        Route::post('/stock-in/searchByName', [StockInController::class, 'searchByName'])->name('stockIn.searchByName');
        Route::post('/stock-in/searchProduct', [ProductController::class, 'dropDownProduct'])->name('stockIn.searchProduct');
        Route::post('/stock-in/searchSupplier', [StockInController::class, 'searchSupplier'])->name('stockIn.searchSupplier');
        Route::post('/stock-in/filterDate', [StockInController::class, 'filter']);

        // Stock Out Route
        Route::resource('/stock-out', StockOutController::class)
        ->middleware('permission:stock_out.index|stock_out.create|stock_out.edit|stock_out.delete')->except(['show']);
        Route::post('/stock-out/searchProduct', [StockOutController::class, 'searchProduct'])->name('stockOut.searchProduct');
        Route::post('/stock-out/searchByName', [ProductController::class, 'dropDownProduct'])->name('stockOut.searchByName');
        Route::post('/stock-out/filterDate', [StockOutController::class, 'filter']);

        // Stock Opname Route
        Route::resource('/stock-opname', StockOpnameController::class)
        ->middleware('permission:stock_opname.index|stock_opname.create|stock_opname.edit|stock_opname.delete');
        Route::post('/stock-opname/searchByName', [StockOpnameController::class, 'searchByName'])->name('stockOpname.searchByName');
        Route::post('/stock-opname/filterDate', [StockOpnameController::class, 'filter'])->name('stockIn.filterDate');
        
        // Employe Route
        Route::resource('/employees', EmployeeController::class)
        ->middleware('permission:employees.index|employees.create|employees.edit|employees.delete');     
        Route::post('/employees/searchEmployeeName', [EmployeeController::class, 'searchEmployeeName'])->name('employee.searchEmployeeName');
        Route::put('/employees/{id}/activate', [EmployeeController::class, 'activate']);
        Route::put('/employees/{id}/deactivate', [EmployeeController::class, 'deactivate']);

        // Route::get('/dashboard', [DashboardController::class, 'index'])
        // ->name('dashboard');

        
    });
    
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::inertia('/inactive', 'Inactive/index');
});

