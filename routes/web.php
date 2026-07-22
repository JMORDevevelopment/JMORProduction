<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SignUpController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Checkout\CartController;

Route::get('/', [HomeController::class, 'index'])->name('home');
// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::get('/sign-up', [SignUpController::class, 'showSignUpForm'])->name('sign-up');
Route::post('/sign-up/validate', [SignUpController::class, 'validate']);

Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('forgot-password');
Route::post('/forgot-pass', [ForgotPasswordController::class, 'sendResetLink'])->name('forgot-pass');

Route::get('/logout', [LogoutController::class, 'logout'])->name('logout');
// ==============================
// CART ROUTES
// ==============================
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/updateItemQty/{rowid}/{qty}', [CartController::class, 'updateItemQty'])->name('cart.update');
Route::post('/cart/couponCode/{code}', [CartController::class, 'couponCode'])->name('cart.coupon');
Route::get('/cart/removeItem/{rowid}', [CartController::class, 'removeItem'])->name('cart.remove');

// ==============================
// CHECKOUT & ORDER ROUTES (HomeController)
// ==============================
Route::get('/checkout', [App\Http\Controllers\HomeController::class, 'check_out'])->name('checkout');
Route::get('/checkout-confirm', [App\Http\Controllers\HomeController::class, 'checkout_confirm'])->name('checkout.confirm');
Route::get('/home/placeOrder', [App\Http\Controllers\HomeController::class, 'placeOrder'])->name('place.order');
Route::get('/home/placeOrderGiftcard', [App\Http\Controllers\HomeController::class, 'placeOrderGiftcard'])->name('place.gift');
Route::post('/home/checkout_from_data', [App\Http\Controllers\HomeController::class, 'checkout_from_data'])->name('checkout.data');
Route::post('/home/chargeCreditCard', [App\Http\Controllers\HomeController::class, 'chargeCreditCard'])->name('charge.card');
Route::get('/checkout-success', [App\Http\Controllers\HomeController::class, 'checkout_success'])->name('checkout.success');

// ==============================
// ADD TO CART (HomeController)
// ==============================
Route::post('/home/addToCartPackages', [App\Http\Controllers\HomeController::class, 'addToCartPackages'])->name('add.cart.packages');
Route::post('/home/addToCartGift', [App\Http\Controllers\HomeController::class, 'addToCartGift'])->name('add.cart.gift');
Route::get('/home/single_package/{id}', [App\Http\Controllers\HomeController::class, 'single_package'])->name('single.package');
// ==============================
// PACKAGES
// ==============================
Route::get('/packages', [App\Http\Controllers\HomeController::class, 'packages_list'])->name('packages');
Route::get('/packages/{category}', [App\Http\Controllers\HomeController::class, 'packages_detail'])->name('packages.detail');