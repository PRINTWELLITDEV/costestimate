<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

use App\Http\Controllers\CostEstimate\MainController;
use App\Http\Controllers\CostEstimate\UserController;
use App\Http\Controllers\CostEstimate\UserProfileController;
use App\Http\Controllers\CostEstimate\SiteController;
use App\Http\Controllers\CostEstimate\PTypesController;
use App\Http\Controllers\CostEstimate\VendorsController;
use App\Http\Controllers\CostEstimate\StockController;
use App\Http\Controllers\CostEstimate\PaperBoardPriceController;

use App\Http\Controllers\HomeController;

// Home route

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/ce');
    } else {
        return app(HomeController::class)->index();
    }
})->name('home');

Route::get('login', function () {
    return redirect()->route('home');
});

// Register
// if (config('app.env') !== 'production') {
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register'])->name('register.submit');
// }

// Password Reset Routes
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// if already logged in, redirect to irms dashboard
// Route::get('login', [LoginController::class, 'showhomeForm'])->name('home');
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');

// Handle login and logout
Route::post('login', [LoginController::class, 'login'])->name('login.submit');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Session check route
Route::get('/ce/session', function () {
    $sessionId = request()->cookie(config('session.cookie'));
    $sessionExists = \DB::table('sessions')->where('id', $sessionId)->exists();
    return response()->json(['valid' => $sessionExists && auth()->check()]);
});

// Dashboard (protected)
Route::get('/ce', function () {
    return view('ce.ce-layouts.home');
})->name('/')->middleware('auth');

Route::prefix('ce')->middleware('auth')->group(function () {
    // User Profile (move this to the bottom and add a constraint)
    Route::get('/u/{userid}', [UserProfileController::class, 'show'])->where('userid', '[A-Za-z0-9]+')->name('ce.userprofile');
    Route::post('/u/{userid}/update', [UserProfileController::class, 'update'])->name('user-profile.update');
    Route::post('/u/{userid}/change-password', [UserProfileController::class, 'changePassword'])->name('user-profile.change-password');

    Route::get('/sites', [SiteController::class, 'index'])->name('sites.index');
    Route::get('/sites/site-list', [SiteController::class, 'siteList'])->name('sites.site-list');
    Route::post('/sites/store', [SiteController::class, 'store'])->name('sites.store');
    Route::put('/sites/update', [SiteController::class, 'update'])->name('sites.update');
    Route::delete('/sites/delete/{site}', [SiteController::class, 'delete'])->name('sites.delete');
    
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/user-list', [UserController::class, 'userlist'])->name('users.userlist');
    Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/update', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/delete/{userid}', [UserController::class, 'delete'])->name('users.delete');

    Route::get('/paper-types', [PTypesController::class, 'index'])->name('ptypes.index');
    Route::get('/paper-types/list', [PTypesController::class, 'PTypeList'])->name('ptypes.ptypeslist');
    Route::post('/paper-types/store', [PTypesController::class, 'store'])->name('ptypes.store');
    Route::put('/paper-types/update', [PTypesController::class, 'update'])->name('ptypes.update');
    Route::delete('/paper-types/delete', [PTypesController::class, 'delete'])->name('ptypes.delete');

    Route::get('/vendors', [VendorsController::class, 'index'])->name('vendors.index');
    Route::get('/vendors/list', [VendorsController::class, 'vendorList'])->name('vendors.vendorlist');
    Route::post('/vendors/store', [VendorsController::class, 'store'])->name('vendors.store');
    Route::put('/vendors/update', [VendorsController::class, 'update'])->name('vendors.update');
    Route::delete('/vendors/delete', [VendorsController::class, 'delete'])->name('vendors.delete');

    Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');
    Route::get('/stocks/list', [StockController::class, 'stockList'])->name('stocks.stocklist');
    Route::get('/stocks/api/ptypes', [StockController::class, 'getPtypes'])->name('stocks.getptypes');
    Route::get('/stocks/add-stock', [StockController::class, 'addStockForm'])->name('stocks.add');
    Route::post('/stocks/store', [StockController::class, 'store'])->name('stocks.store');
    Route::get('/stocks/edit-stock', [StockController::class, 'editStockForm'])->name('stocks.edit');
    Route::put('/stocks/update', [StockController::class, 'update'])->name('stocks.update');
    Route::delete('/stocks/delete', [StockController::class, 'delete'])->name('stocks.delete');

    Route::get('/paper-board-price', [PaperBoardPriceController::class, 'index'])->name('paperboardprice.index');
    Route::get('/paper-board-price/list', [PaperBoardPriceController::class, 'pricingList'])->name('paperboardprice.pricinglist');
    Route::get('/paper-board-price/api/ptypes', [PaperBoardPriceController::class, 'getPTypes'])->name('paperboardprice.api.ptypes');
    Route::get('/paper-board-price/api/vendors', [PaperBoardPriceController::class, 'getVendors'])->name('paperboardprice.api.vendors');
    Route::get('/paper-board-price/api/stocks', [PaperBoardPriceController::class, 'getStocks'])->name('paperboardprice.api.stocks');
    Route::get('/paper-board-price/api/um', [PaperBoardPriceController::class, 'getUM'])->name('paperboardprice.api.um');
    Route::post('/paper-board-price/store', [PaperBoardPriceController::class, 'store'])->name('paperboardprice.store');
    Route::put('/paper-board-price/update', [PaperBoardPriceController::class, 'update'])->name('paperboardprice.update');
    Route::delete('/paper-board-price/delete', [PaperBoardPriceController::class, 'delete'])->name('paperboardprice.delete');

    Route::get('/paper-board-price/calculator', [PaperBoardPriceController::class, 'pbpcalculatorForm'])->name('paperboardprice.calculator');
});


