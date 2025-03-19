<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Admin\{
    AdminAuthController,
    CompanyController,
    UserController,
    AdminTaxController
};
use App\Http\Controllers\Company\{
    CompanyAuthController,
    VariationController,
    TaxController,
    ItemController,
    BankController,
    VendorController,
    CustomerController,
    PurchesBookController,
    SalesBookController,
    ReceiptBookVoucherController,
    PaymentBookController,
    PurchesReportController,
    SalesReportController,
    ReceiptBookReportController,
    PaymentBookReportController,
    BankAndCashMangementController,
    BankAndCashReportController,
    StockReportController,
    GstReportController,
    SubcompanyController
};


use App\Http\Controllers\Ajax\{
    LocationController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you can register web routes for your application.
| All routes defined here are assigned to the "web" middleware group.
|
*/

// Public Routes
Route::get('/', [AdminAuthController::class, 'index']);  // Default landing page
Route::get('/home', [AdminAuthController::class, 'index']);  // Redirect to the home page

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Routes for admin functionalities, prefixed with 'admin' and named with 'admin.'
|
*/

Route::prefix('admin')->name('admin.')->group(function () {

    // Admin Authentication Routes
    Route::controller(AdminAuthController::class)->group(function () {
        Route::get('login', 'login')->name('login');
        Route::post('login', 'postLogin')->name('login.post');
        Route::get('forget-password', 'showForgetPasswordForm')->name('forget.password.get');
        Route::post('forget-password', 'submitForgetPasswordForm')->name('forget.password.post');
        Route::get('reset-password/{token}', 'showResetPasswordForm')->name('reset.password.get');
        Route::post('reset-password', 'submitResetPasswordForm')->name('reset.password.post');
    });

    // Routes requiring 'admin' middleware
    Route::middleware('admin')->group(function () {

        // Admin Dashboard and Profile Routes
        Route::controller(AdminAuthController::class)->group(function () {
            Route::get('dashboard', 'adminDashboard')->name('dashboard');
            Route::get('change-password', 'changePassword')->name('change.password');
            Route::post('update-password', 'updatePassword')->name('update.password');
            Route::get('logout', 'logout')->name('logout');
            Route::get('profile', 'adminProfile')->name('profile');
            Route::post('profile', 'updateAdminProfile')->name('update.profile');
        });

        // Admin Company Management Routes
        Route::prefix('company')->name('company.')->controller(CompanyController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('allcompany', 'getallCompany')->name('allcompany');
            Route::post('store', 'store')->name('store');
            Route::post('status', 'companyStatus')->name('status');
            Route::delete('delete/{id}', 'destroy')->name('destroy');
            Route::get('company/{id}', 'getCompany')->name('get');
            Route::get('{id}', 'show')->name('show');
            Route::get('logo/{id}', 'logo')->name('logo');
            Route::post('updatelogo', 'updatelogo')->name('update.logo');
            Route::post('update', 'updateCompany')->name('update');
        });

        // Admin User Management Routes
        Route::prefix('users')->name('user.')->controller(UserController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('all', 'getall')->name('alluser');
            Route::post('store', 'store')->name('store');
            Route::post('status', 'status')->name('status');
            Route::delete('delete/{id}', 'destroy')->name('destroy');
            Route::get('company/{id}', 'get')->name('get');
            Route::get('{id}', 'show')->name('show');
            Route::post('update', 'update')->name('update');
        });


        // Admin Tax Management Routes
        Route::prefix('tax')->name('tax.')->controller(AdminTaxController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('all', 'getall')->name('getall');
            Route::post('store', 'store')->name('store');
            Route::post('status', 'status')->name('status');
            Route::delete('delete/{id}', 'destroy')->name('destroy');
            Route::get('get/{id}', 'get')->name('get');
            Route::post('update', 'update')->name('update');
        });

    });
});

/*
|--------------------------------------------------------------------------
| Company Routes
|--------------------------------------------------------------------------
|
| Routes for company functionalities, prefixed with 'company' and named with 'company.'
|
*/

Route::prefix('company')->name('company.')->group(function () {

    // Company Authentication Routes
    Route::controller(CompanyAuthController::class)->group(function () {
        Route::get('login', 'login')->name('login');
        Route::post('login', 'postLogin')->name('login.post');
        Route::get('forget-password', 'showForgetPasswordForm')->name('forget.password.get');
        Route::post('forget-password', 'submitForgetPasswordForm')->name('forget.password.post');
        Route::get('reset-password/{token}', 'showResetPasswordForm')->name('reset.password.get');
        Route::post('reset-password', 'submitResetPasswordForm')->name('reset.password.post');
    });

    // Routes requiring 'user' middleware
    Route::middleware('user')->group(function () {

        // Company Dashboard and Profile Routes
        Route::controller(CompanyAuthController::class)->group(function () {
            Route::get('dashboard', 'companyDashboard')->name('dashboard');
            Route::get('change-password', 'changePassword')->name('change.password');
            Route::post('update-password', 'updatePassword')->name('update.password');
            Route::get('logout', 'logout')->name('logout');
            Route::get('profile', 'companyProfile')->name('profile');
            Route::post('profile', 'updatecompanyProfile')->name('update.profile');
        });

        // Resource Management Routes (Variation, Tax, Item, Vendor, Customer)
        foreach (['variation', 'tax', 'item', 'vendor', 'customer', 'subcompany','transport'] as $resource) {
            Route::prefix($resource)->name("$resource.")->group(function () use ($resource) {
                $controller = "App\Http\Controllers\Company\\" . ucfirst($resource) . "Controller";
                Route::get('/', [$controller, 'index'])->name('index');
                Route::get('all', [$controller, 'getall'])->name('getall');
                Route::post('store', [$controller, 'store'])->name('store');
                Route::post('status', [$controller, 'status'])->name('status');
                Route::delete('delete/{id}', [$controller, 'destroy'])->name('destroy');
                Route::get('get/{id}', [$controller, 'get'])->name('get');
                Route::post('update', [$controller, 'update'])->name('update');
            });
        }

        // Purchase Book Management Routes
        Route::prefix('purches-book')->name('purches.book.')->controller(PurchesBookController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('all', 'getall')->name('getall');
            Route::get('/add', 'add')->name('add');
            Route::post('/store', 'store')->name('store');
            Route::delete('/delete/{id}', 'destroy')->name('destroy');
            Route::get('/edit/{id}', 'edit')->name('edit'); // Edit route
            Route::get('/view/{id}', 'view')->name('view'); // View route
            Route::put('/update/{id}', 'update')->name('update'); // Update route
            Route::get('/p-return/{id}', 'preturn')->name('preturn'); // Edit route
            Route::post('/p-return/save', 'preturn_update')->name('preturn.save');
            Route::get('/return/add', 'preturnadd')->name('return.add');
            Route::get('/purches/return', 'indexreturn')->name('return.list');
            Route::get('all/purches/return', 'getallreturn')->name('getall.return');
        });

        // Sales Book Management Routes
        Route::prefix('sales-book')->name('sales.book.')->controller(SalesBookController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('all', 'getall')->name('getall');
            Route::get('/add', 'add')->name('add');
            Route::post('/store', 'store')->name('store');
            Route::delete('/delete/{id}', 'destroy')->name('destroy');
            Route::get('/edit/{id}', 'edit')->name('edit'); // Edit route
            Route::get('/view/{id}', 'view')->name('view'); // Edit route
            Route::put('/update/{id}', 'update')->name('update'); // Update route
            Route::get('/s-return/{id}', 'sreturn')->name('sreturn'); // Edit route
            Route::post('/s-return/save/{id}', 'sreturn_update')->name('spreturn.save');
            Route::get('/sales/return', 'indexreturn')->name('return.list');
            Route::get('all/sales/return', 'getallreturn')->name('getall.return');
        });

        // Receipt Book Voucher Management Routes
        Route::prefix('receipt-book-voucher')->name('receipt.book.voucher.')->controller(ReceiptBookVoucherController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('all', 'getall')->name('getall');
            Route::get('/add', 'add')->name('add');
            Route::post('/store', 'store')->name('store');
            Route::delete('/delete/{id}', 'destroy')->name('destroy');
            Route::get('/edit/{id}', 'edit')->name('edit'); // Edit route
            Route::get('/print/{id}', 'print')->name('print'); // Print route
            Route::put('/update/{id}', 'update')->name('update'); // Update route
        });

        // Payment Book Management Routes
        Route::prefix('payment-book')->name('payment.book.')->controller(PaymentBookController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('all', 'getall')->name('getall');
            Route::get('/add', 'add')->name('add');
            Route::post('/store', 'store')->name('store');
            Route::delete('/delete/{id}', 'destroy')->name('destroy');
            Route::get('/edit/{id}', 'edit')->name('edit'); // Edit route
            Route::get('/print/{id}', 'print')->name('print'); // Print route
            Route::put('/update/{id}', 'update')->name('update'); // Update route
        });


        // Payment Report Management Routes
        Route::prefix('purches-report')->name('purches.report.')->controller(PurchesReportController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('all', 'getall')->name('getall');
            Route::get('/print/{id}', 'print')->name('print'); // Print route
        });


        // Sales Report Management Routes
        Route::prefix('sales-report')->name('sales.report.')->controller(SalesReportController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('all', 'getall')->name('getall');
            Route::get('/print/{id}', 'print')->name('print'); // Print route
        });


        // Sales Report Management Routes
        Route::prefix('receipt-report')->name('receipt.report.')->controller(ReceiptBookReportController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('all', 'getall')->name('getall');
            Route::get('/print/{id}', 'print')->name('print'); // Print route
        });

        // Sales Report Management Routes
        Route::prefix('payment-report')->name('payment.report.')->controller(PaymentBookReportController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('all', 'getall')->name('getall');
            Route::get('/print/{id}', 'print')->name('print'); // Print route
        });


        // Bank And Cash Management Routes
        Route::prefix('bank-and-cash')->name('bank.and.cash.')->controller(BankAndCashMangementController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('all', 'getall')->name('getall');
            Route::post('/store', 'store')->name('store');
            Route::delete('/delete/{id}', 'destroy')->name('destroy');
            Route::get('get/{id}', 'get')->name('get');
            Route::post('update', 'update')->name('update');
            Route::delete('delete/{id}','destroy')->name('destroy');
        });

        // Sales Report Management Routes
        Route::prefix('contra')->name('contra.report.')->controller(BankAndCashReportController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('all', 'getall')->name('getall');
        });

        Route::prefix('bank-and-cash-report')->name('bank.and.cash.report.')->controller(BankAndCashReportController::class)->group(function () {
            Route::get('/', 'bankindex')->name('bankindex');
            Route::get('all', 'getall')->name('getall');
        });

        Route::prefix('stock-report')->name('stock.report.')->controller(StockReportController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('all', 'getall')->name('getall');
        });


        Route::prefix('gst-report')->name('gst.report.')->controller(GstReportController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/filter', 'filter')->name('filter');
        });

    });
});

/*
|--------------------------------------------------------------------------
| Ajax Routes
|--------------------------------------------------------------------------
|
| Routes for Ajax functionalities, prefixed with 'ajax' and named with 'ajax.'
|
*/

Route::prefix('ajax')->name('ajax.')->group(function () {

    // Company Authentication Routes
    Route::controller(LocationController::class)->group(function () {
        Route::get('/getCities/{state}', 'getCities')->name('getCities');
        Route::get('/getPincodes/{city}', 'getPincodes')->name('getPincodes');
        Route::post('/check-stock', 'checkStock')->name('checkStock');
        Route::get('/category/{sub_company}', 'getCategory')->name('getCategory');
        Route::get('/get-vendors/{sub_company_id}', 'getVendors')->name('getVendors');
        Route::get('/get-categories/{sub_company_id}', 'getCategories')->name('getCategories');
        Route::get('/get-items/{category_id}', 'getItems')->name('getItems');
        Route::get('/get-customers/{sub_compnay_id}', 'getCustomers')->name('getCustomers');
        Route::get('/get-purchase-details/{id}', 'getPurchaseDetails')->name('getPurchaseDetails');
    });
});

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
|
| Routes that require user authentication
|
*/

Route::middleware(['auth'])->group(function () {
    // Define routes that require user authentication here
});
