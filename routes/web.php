<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SignUpController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Checkout\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PackageController;

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
// CHECKOUT & ORDER ROUTES
// ==============================
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::get('/checkout-confirm', [CheckoutController::class, 'confirm'])->name('checkout.confirm');
Route::get('/home/placeOrder', [CheckoutController::class, 'placeOrder'])->name('place.order');
Route::get('/home/placeOrderGiftcard', [CheckoutController::class, 'placeOrderGiftCard'])->name('place.gift');
Route::post('/home/checkout_from_data', [CheckoutController::class, 'saveFormData'])->name('checkout.data');
Route::post('/home/chargeCreditCard', [PaymentController::class, 'chargeCreditCard'])->name('charge.card');
Route::get('/checkout-success', [CheckoutController::class, 'success'])->name('checkout.success');

// ==============================
// ADD TO CART
// ==============================
Route::post('/home/addToCartPackages', [CartController::class, 'addPackages'])->name('add.cart.packages');
Route::post('/home/addToCartGift', [CartController::class, 'addGiftCard'])->name('add.cart.gift');
Route::get('/home/single_package/{id}', [PackageController::class, 'single'])->name('single.package');
// ==============================
// PACKAGES
// ==============================
Route::get('/packages', [PackageController::class, 'list'])->name('packages');
Route::get('/packages/{category}', [PackageController::class, 'detail'])->name('packages.detail');
