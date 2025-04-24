<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiscountTransactionController;
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
        Route::get('/transactions/searchProductName', [TransactionController::class, 'searchProductName']);
        Route::resource('/', TransactionController::class);
        Route::post('/transactions/addToCart', [TransactionController::class, 'cart']);
        Route::post('/transactions/searchByBarcode', [TransactionController::class, 'searchByBarcode']);
        Route::post('/transactions/destroyCart', [TransactionController::class, 'destroyCart']);
        Route::post('/transactions/printReceipt', [TransactionController::class, 'printReceipt']);

        // Products Route
        Route::get('/products/searchProductName', [ProductController::class, 'searchProductName']);
        Route::get('/products/data', [ProductController::class, 'data']);
        Route::resource('/products', ProductController::class)
        ->middleware('permission:products.index|products.create|products.edit|products.delete');
        Route::post('/products/dropDownProduct', [ProductController::class, 'dropDownProduct']);

        // Categories Route
        Route::get('/categories/searchCategoryName', [CategoryController::class, 'searchCategoryName']);
        Route::resource('/categories', CategoryController::class)
        ->middleware('permission:categories.index|categories.create|categories.edit|categories.delete');
        Route::post('/categories/dropDownCategory', [CategoryController::class, 'dropDownCategory']);

        // Sales Route
        Route::get('/sales', [SaleController::class, 'index'])->middleware('permission:sales.index');
        Route::get('/sales/filterDate', [SaleController::class, 'filterDate']);
        Route::post('/sales/transaction_detail_filter', [SaleController::class, 'transaction_detail_filter']);

        // Profits Route
        Route::get('/profits/filterDate', [ProfitController::class, 'filterDate']);
        Route::get('/profits', [ProfitController::class, 'index'])->middleware('permission:profits.index');

        // Roles Route
        Route::get('/roles/searchRolesName', [RoleController::class, 'searchRoles'])->name('roles.searchRoles');
        Route::resource('/roles', RoleController::class)
        ->middleware('permission:roles.index|roles.create|roles.edit|roles.delete');
        Route::post('/roles/dropDownRole', [RoleController::class, 'dropDownRole']);

        // Supplier Route
        Route::get('/suppliers/searchSupplierName', [SupplierController::class, 'searchSupplierName']);
        Route::resource('/suppliers', SupplierController::class)
        ->middleware('permission:suppliers.index|suppliers.create|suppliers.edit|suppliers.delete');
        
        // Customer Route
        Route::get('/customers/searchCustomerName', [CustomersController::class, 'searchCustomerName']);
        Route::resource('/customers', CustomersController::class)
        ->middleware('permission:customers.index|customers.create|customers.edit|customers.delete');
        Route::post('/customers/searchCustomerByPhone', [CustomersController::class, 'searchCustomerByPhone']);
        
        // Stock-in Route
        Route::get('/stock-in/searchByName', [StockInController::class, 'searchByName'])->name('stockIn.searchByName');
        Route::get('/stock-in/filterDate', [StockInController::class, 'filterDate']);
        Route::resource('/stock-in', StockInController::class)
        ->middleware('permission:stock_in.index|stock_in.create')->except(['show']);
        Route::post('/stock-in/searchSupplier', [StockInController::class, 'searchSupplier'])->name('stockIn.searchSupplier');

        // Stock Out Route
        Route::get('/stock-out/searchByName', [StockOutController::class, 'searchByName'])->name('stockOut.searchByName');
        Route::get('/stock-out/filterDate', [StockOutController::class, 'filterDate']);
        Route::resource('/stock-out', StockOutController::class)
        ->middleware('permission:stock_out.index|stock_out.create')->except(['show']);

        // Stock Opname Route
        Route::get('/stock-opname/searchByName', [StockOpnameController::class, 'searchByName'])->name('stockOpname.searchByName');
        Route::get('/stock-opname/filterDate', [StockOpnameController::class, 'filterDate'])->name('stockIn.filterDate');
        Route::resource('/stock-opname', StockOpnameController::class)
        ->middleware('permission:stock_opname.index|stock_opname.create|stock_opname.edit|stock_opname.delete');
        
        // Employe Route
        Route::get('/employees/searchEmployeesName', [EmployeeController::class, 'searchEmployeesName'])->name('employee.searchEmployeeName');
        Route::resource('/employees', EmployeeController::class)
        ->middleware('permission:employees.index|employees.create|employees.edit|employees.delete');     
        Route::get('/employee/{id}/activate', [EmployeeController::class, 'activate'])->name('employee.activate');
        Route::get('/employee/{id}/deactivate', [EmployeeController::class, 'deactivate'])->name('employee.deactivate');
        
        // Discount Transaction Route
        Route::get('/discount-transactions/searchDiscountName', [DiscountTransactionController::class, 'searchDiscountName'])->name('discount.searchDiscountName');
        Route::resource('/discount-transactions', DiscountTransactionController::class)
        ->middleware('permission:discounts.index|discounts.create|discounts.delete');    
        Route::post('/discount-transactions/searchDiscountCode', [DiscountTransactionController::class, 'searchDiscountCode'])->name('discount.searchDiscountCode');
        Route::put('/update-discount/{discount}', [DiscountTransactionController::class, 'update'])
        ->name('discount-transactions.update.custom')
        ->middleware('permission:discounts.edit'); 
        
        // Dashboard Route
        Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

        
    });
    
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::inertia('/inactive', 'Inactive/index');
});

