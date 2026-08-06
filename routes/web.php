<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\SignUpController;
use App\Http\Controllers\Checkout\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PaymentController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// ==============================
// AUTHENTICATION ROUTES
// ==============================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

Route::get('/sign-up', [SignUpController::class, 'showSignUpForm'])->name('sign-up');
Route::post('/sign-up/validate', [SignUpController::class, 'validate'])->name('sign-up.validate');

Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('forgot-password');
Route::post('/forgot-pass', [ForgotPasswordController::class, 'sendResetLink'])->name('forgot-pass');

Route::get('/logout', [LogoutController::class, 'logout'])->name('logout');

// ==============================
// CONTACT ROUTES
// ==============================
Route::get('/contact', fn () => 'Hello World')->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

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

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout')->middleware('auth.user');
Route::get('/checkout-confirm', [CheckoutController::class, 'confirm'])->name('checkout.confirm')->middleware('auth.user');
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

// ==============================
// CONTENT PAGE STUBS (placeholder until real pages are built)
// ==============================
Route::get('/it-service-providers-in-new-jersey-for-homes-and-businesses', fn () => 'Hello World')->name('it-service-providers-in-new-jersey-for-homes-and-businesses');
Route::get('/network-cyber-security-internet-safety', fn () => 'Hello World')->name('network-cyber-security-internet-safety');
Route::get('/hardware-firewalls-and-utm-devices', fn () => 'Hello World')->name('hardware-firewalls-and-utm-devices');
Route::get('/antivirus-malware-ransomware-vunerability-endpoint-management-solutions', fn () => 'Hello World')->name('antivirus-malware-ransomware-vunerability-endpoint-management-solutions');
Route::get('/ai-solutions-for-businesses-consumers-safe-secure-ai-integrations-in-nj-and-beyond', fn () => 'Hello World')->name('ai-solutions-for-businesses-consumers-safe-secure-ai-integrations-in-nj-and-beyond');
Route::get('/technical-relocation-services', fn () => 'Hello World')->name('technical-relocation-services');

Route::get('/about-us', fn () => 'Hello World')->name('about-us');
Route::get('/about', fn () => 'Hello World')->name('about');
Route::get('/our-mission', fn () => 'Hello World')->name('our-mission');
Route::get('/our-dei-diversiety-equity-inclusion-non-discrimination-policy', fn () => 'Hello World')->name('our-dei-diversiety-equity-inclusion-non-discrimination-policy');
Route::get('/why-choose-jmor', fn () => 'Hello World')->name('why-choose-jmor');
Route::get('/testimonials', fn () => 'Hello World')->name('testimonials');
Route::get('/media-relations', fn () => 'Hello World')->name('media-relations');

Route::get('/technology-guides-it-resources-the-jmor-connection-inc', fn () => 'Hello World')->name('technology-guides-it-resources-the-jmor-connection-inc');
Route::get('/the-jmor-blog', fn () => 'Hello World')->name('the-jmor-blog');
Route::get('/case-studies', fn () => 'Hello World')->name('case-studies');
Route::get('/jmor-shows', fn () => 'Hello World')->name('jmor-shows');
Route::get('/events', fn () => 'Hello World')->name('events');
Route::get('/the-jmor-store', fn () => 'Hello World')->name('the-jmor-store');

Route::get('/refund-policy', fn () => 'Hello World')->name('refund-policy');
Route::get('/privacy-policy', fn () => 'Hello World')->name('privacy-policy');
Route::get('/terms', fn () => 'Hello World')->name('terms');
Route::get('/sitemap', fn () => 'Hello World')->name('sitemap');

Route::get('/solutions', fn () => 'Hello World')->name('solutions');
Route::get('/service/{link}', fn () => 'Hello World')->name('service.detail');

Route::get('/attorneys-law-firms', fn () => 'Hello World')->name('attorneys-law-firms');
Route::get('/cpa-firms', fn () => 'Hello World')->name('cpa-firms');
Route::get('/dentists', fn () => 'Hello World')->name('dentists');
Route::get('/general-practice-doctors', fn () => 'Hello World')->name('general-practice-doctors');
Route::get('/fast-food-restaurants', fn () => 'Hello World')->name('fast-food-restaurants');
Route::get('/manufacturers', fn () => 'Hello World')->name('manufacturers');
Route::get('/office-managers', fn () => 'Hello World')->name('office-managers');

Route::get('/gift-card', fn () => 'Hello World')->name('gift-card');
Route::get('/dashboard', fn () => 'Hello World')->name('dashboard');
Route::get('/search', fn () => 'Hello World')->name('search');
Route::post('/search', fn () => 'Hello World')->name('search.submit');

// ==============================
// DB MENU LINK STUBS (extra menu items surfaced in the megamenu)
// ==============================
Route::get('/we-serve', fn () => 'Hello World')->name('we-serve');
Route::get('/social', fn () => 'Hello World')->name('social');
Route::get('/request-information', fn () => 'Hello World')->name('request-information');
Route::get('/custom-built-technology-solutions-in-nj', fn () => 'Hello World')->name('custom-built-technology-solutions-in-nj');
Route::get('/it-support-solutions-for-new-jersey-businesses-and-homes-the-jmor-connection', fn () => 'Hello World')->name('it-support-solutions-for-new-jersey-businesses-and-homes-the-jmor-connection');
Route::get('/print-management-solution', fn () => 'Hello World')->name('print-management-solution');
Route::get('/remote-employee-engagement-solutions', fn () => 'Hello World')->name('remote-employee-engagement-solutions');
Route::get('/covid19-remote-friendly-support-service', fn () => 'Hello World')->name('covid19-remote-friendly-support-service');
Route::get('/linktree', fn () => 'Hello World')->name('linktree');
Route::get('/jmor-tech-byte-sized-blunders-laugh-and-learn', fn () => 'Hello World')->name('jmor-tech-byte-sized-blunders-laugh-and-learn');
Route::get('/blog', fn () => 'Hello World')->name('blog');
Route::get('/category-jmor-shows/shows-i-appeared-as-a-guest-on', fn () => 'Hello World')->name('category-jmor-shows/shows-i-appeared-as-a-guest-on');
Route::get('/category-jmor-shows/jmor-tech-talk-show', fn () => 'Hello World')->name('category-jmor-shows/jmor-tech-talk-show');
Route::get('/category-jmor-shows/jmor-unboxings', fn () => 'Hello World')->name('category-jmor-shows/jmor-unboxings');
Route::get('/category-jmor-shows/jmor-reviews', fn () => 'Hello World')->name('category-jmor-shows/jmor-reviews');
Route::get('/recommended', fn () => 'Hello World')->name('recommended');
Route::get('/random-acts-of-kindness', fn () => 'Hello World')->name('random-acts-of-kindness');
Route::get('/jmor-tech-talk-show', fn () => 'Hello World')->name('jmor-tech-talk-show');
Route::get('/media-resources', fn () => 'Hello World')->name('media-resources');
Route::get('/media-video', fn () => 'Hello World')->name('media-video');
Route::get('/press-releases', fn () => 'Hello World')->name('press-releases');
Route::get('/brand-guidelines', fn () => 'Hello World')->name('brand-guidelines');
Route::get('/media-inquiries', fn () => 'Hello World')->name('media-inquiries');
Route::get('/pcs', fn () => 'Hello World')->name('pcs');
Route::get('/servers', fn () => 'Hello World')->name('servers');
Route::get('/laptops', fn () => 'Hello World')->name('laptops');
Route::get('/accessories', fn () => 'Hello World')->name('accessories');
Route::get('/it-tech-support-services-in-nj', fn () => 'Hello World')->name('it-tech-support-services-in-nj');
Route::get('/custom-built-solutions', fn () => 'Hello World')->name('custom-built-solutions');
Route::get('/nj-technical-relocation-services', fn () => 'Hello World')->name('nj-technical-relocation-services');
