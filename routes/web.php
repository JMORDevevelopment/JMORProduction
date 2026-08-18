<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\SignUpController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BrandGuidelineController;
use App\Http\Controllers\CaseStudyController;
use App\Http\Controllers\Checkout\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MediaInquiryController;
use App\Http\Controllers\MediaResourceController;
use App\Http\Controllers\MediaVideoController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PressReleaseController;
use App\Http\Middleware\CheckUserLogin;

Route::get('/', [HomeController::class, 'index'])->name('home');

// ==============================
// USER DASHBOARD ROUTES
// ==============================
Route::middleware(CheckUserLogin::class)->prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders', [DashboardController::class, 'orders'])->name('dashboard.orders');
    Route::get('/order_invoice/{order_id}', [DashboardController::class, 'orderInvoice'])->name('dashboard.order_invoice');
    Route::get('/user_settings', [DashboardController::class, 'userSettings'])->name('dashboard.user_settings');
    Route::get('/user_settings_update/{user_id}', [DashboardController::class, 'userSettingsUpdate'])->name('dashboard.user_settings_update');
    Route::post('/user_settings_validate/{user_id}', [DashboardController::class, 'userSettingsValidate'])->name('dashboard.user_settings_validate');

    Route::get('/giftcard', [DashboardController::class, 'giftcard'])->name('dashboard.giftcard');
    Route::get('/giftcard_invoice/{order_id}', [DashboardController::class, 'giftcardInvoice'])->name('dashboard.giftcard_invoice');
    Route::get('/testimonial', [DashboardController::class, 'testimonial'])->name('dashboard.testimonial');
    Route::get('/testimonial_add/{id?}', [DashboardController::class, 'testimonialAdd'])->name('dashboard.testimonial_add');
    Route::post('/testimonial_validate/{id?}', [DashboardController::class, 'testimonialValidate'])->name('dashboard.testimonial_validate');
});

// ==============================
// AUTHENTICATION ROUTES
// ==============================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

Route::get('/sign-up', [SignUpController::class, 'showSignUpForm'])->name('sign-up');
Route::post('/sign-up/validate', [SignUpController::class, 'validate'])->name('sign-up.validate');

Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('forgot-password');
Route::post('/forgot-pass', [ForgotPasswordController::class, 'sendResetLink'])->name('forgot-pass');

Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// ==============================
// CONTACT ROUTES
// ==============================
Route::get('/contact', fn () => view('frontend.coming-soon'))->name('contact');
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
// NEWS (ported from CI: home/news_list + home/news_detail)
// ==============================
Route::get('/news', [NewsController::class, 'list'])->name('news');
Route::get('/news/index/{start}', [NewsController::class, 'list'])->name('news.index');
Route::get('/news/{link}', [NewsController::class, 'detail'])->name('news.detail');

// Blog detail route used by the news page sidebar; the full blog feature ships separately.
Route::get('/blog/{link}', [BlogController::class, 'detail'])->name('blog.detail');

// ==============================
// CONTENT PAGE STUBS (placeholder until real pages are built)
// ==============================
Route::get('/it-service-providers-in-new-jersey-for-homes-and-businesses', fn () => view('frontend.coming-soon'))->name('it-service-providers-in-new-jersey-for-homes-and-businesses');
Route::get('/network-cyber-security-internet-safety', fn () => view('frontend.coming-soon'))->name('network-cyber-security-internet-safety');
Route::get('/hardware-firewalls-and-utm-devices', fn () => view('frontend.coming-soon'))->name('hardware-firewalls-and-utm-devices');
Route::get('/antivirus-malware-ransomware-vunerability-endpoint-management-solutions', fn () => view('frontend.coming-soon'))->name('antivirus-malware-ransomware-vunerability-endpoint-management-solutions');
Route::get('/ai-solutions-for-businesses-consumers-safe-secure-ai-integrations-in-nj-and-beyond', fn () => view('frontend.coming-soon'))->name('ai-solutions-for-businesses-consumers-safe-secure-ai-integrations-in-nj-and-beyond');
Route::get('/technical-relocation-services', fn () => view('frontend.coming-soon'))->name('technical-relocation-services');

Route::get('/about-us', fn () => view('frontend.coming-soon'))->name('about-us');
Route::get('/about', fn () => view('frontend.coming-soon'))->name('about');
Route::get('/our-mission', fn () => view('frontend.coming-soon'))->name('our-mission');
Route::get('/our-dei-diversiety-equity-inclusion-non-discrimination-policy', fn () => view('frontend.coming-soon'))->name('our-dei-diversiety-equity-inclusion-non-discrimination-policy');
Route::get('/why-choose-jmor', fn () => view('frontend.coming-soon'))->name('why-choose-jmor');
Route::get('/testimonials', fn () => view('frontend.coming-soon'))->name('testimonials');
Route::get('/media-relations', fn () => view('frontend.coming-soon'))->name('media-relations');

Route::get('/technology-guides-it-resources-the-jmor-connection-inc', fn () => view('frontend.coming-soon'))->name('technology-guides-it-resources-the-jmor-connection-inc');
Route::get('/the-jmor-blog', fn () => view('frontend.coming-soon'))->name('the-jmor-blog');
Route::get('/events', [EventController::class, 'posts'])->name('events');
Route::get('/events/{link}', [EventController::class, 'detail'])->name('events.detail');

Route::get('/case-studies', [CaseStudyController::class, 'posts'])->name('case-studies');
Route::get('/case-studies/{link}', [CaseStudyController::class, 'detail'])->name('case-studies.detail');

Route::get('/jmor-shows', fn () => view('frontend.coming-soon'))->name('jmor-shows');
Route::get('/the-jmor-store', fn () => view('frontend.coming-soon'))->name('the-jmor-store');

Route::get('/refund-policy', fn () => view('frontend.coming-soon'))->name('refund-policy');
Route::get('/privacy-policy', fn () => view('frontend.coming-soon'))->name('privacy-policy');
Route::get('/terms', fn () => view('frontend.coming-soon'))->name('terms');
Route::get('/sitemap', fn () => view('frontend.coming-soon'))->name('sitemap');

Route::get('/solutions', fn () => view('frontend.coming-soon'))->name('solutions');
Route::get('/service/{link}', fn () => view('frontend.coming-soon'))->name('service.detail');

Route::get('/attorneys-law-firms', fn () => view('frontend.coming-soon'))->name('attorneys-law-firms');
Route::get('/cpa-firms', fn () => view('frontend.coming-soon'))->name('cpa-firms');
Route::get('/dentists', fn () => view('frontend.coming-soon'))->name('dentists');
Route::get('/general-practice-doctors', fn () => view('frontend.coming-soon'))->name('general-practice-doctors');
Route::get('/fast-food-restaurants', fn () => view('frontend.coming-soon'))->name('fast-food-restaurants');
Route::get('/manufacturers', fn () => view('frontend.coming-soon'))->name('manufacturers');
Route::get('/office-managers', fn () => view('frontend.coming-soon'))->name('office-managers');

Route::get('/gift-card', fn () => view('frontend.coming-soon'))->name('gift-card');
Route::get('/search', fn () => view('frontend.coming-soon'))->name('search');
Route::post('/search', fn () => view('frontend.coming-soon'))->name('search.submit');

// ==============================
// DB MENU LINK STUBS (extra menu items surfaced in the megamenu)
// ==============================
Route::get('/we-serve', fn () => view('frontend.coming-soon'))->name('we-serve');
Route::get('/social', fn () => view('frontend.coming-soon'))->name('social');
Route::get('/request-information', fn () => view('frontend.coming-soon'))->name('request-information');
Route::get('/custom-built-technology-solutions-in-nj', fn () => view('frontend.coming-soon'))->name('custom-built-technology-solutions-in-nj');
Route::get('/it-support-solutions-for-new-jersey-businesses-and-homes-the-jmor-connection', fn () => view('frontend.coming-soon'))->name('it-support-solutions-for-new-jersey-businesses-and-homes-the-jmor-connection');
Route::get('/print-management-solution', fn () => view('frontend.coming-soon'))->name('print-management-solution');
Route::get('/remote-employee-engagement-solutions', fn () => view('frontend.coming-soon'))->name('remote-employee-engagement-solutions');
Route::get('/covid19-remote-friendly-support-service', fn () => view('frontend.coming-soon'))->name('covid19-remote-friendly-support-service');
Route::get('/linktree', fn () => view('frontend.coming-soon'))->name('linktree');
Route::get('/jmor-tech-byte-sized-blunders-laugh-and-learn', fn () => view('frontend.coming-soon'))->name('jmor-tech-byte-sized-blunders-laugh-and-learn');
Route::get('/blog', fn () => view('frontend.coming-soon'))->name('blog');
Route::get('/category-jmor-shows/shows-i-appeared-as-a-guest-on', fn () => view('frontend.coming-soon'))->name('category-jmor-shows/shows-i-appeared-as-a-guest-on');
Route::get('/category-jmor-shows/jmor-tech-talk-show', fn () => view('frontend.coming-soon'))->name('category-jmor-shows/jmor-tech-talk-show');
Route::get('/category-jmor-shows/jmor-unboxings', fn () => view('frontend.coming-soon'))->name('category-jmor-shows/jmor-unboxings');
Route::get('/category-jmor-shows/jmor-reviews', fn () => view('frontend.coming-soon'))->name('category-jmor-shows/jmor-reviews');
Route::get('/recommended', fn () => view('frontend.coming-soon'))->name('recommended');
Route::get('/random-acts-of-kindness', fn () => view('frontend.coming-soon'))->name('random-acts-of-kindness');
Route::get('/jmor-tech-talk-show', fn () => view('frontend.coming-soon'))->name('jmor-tech-talk-show');
Route::get('/media-resources', [MediaResourceController::class, 'posts'])->name('media-resources');
Route::get('/media-resources/{link}', [MediaResourceController::class, 'detail'])->name('media-resources.detail');
Route::get('/media-video', [MediaVideoController::class, 'posts'])->name('media-video');
Route::get('/media-video/{link}', [MediaVideoController::class, 'detail'])->name('media-video.detail');
Route::get('/press-releases', [PressReleaseController::class, 'posts'])->name('press-releases');
Route::get('/press-releases/{link}', [PressReleaseController::class, 'detail'])->name('press-releases.detail');
Route::get('/brand-guidelines', [BrandGuidelineController::class, 'posts'])->name('brand-guidelines');
Route::get('/brand-guidelines/{link}', [BrandGuidelineController::class, 'detail'])->name('brand-guidelines.detail');
Route::get('/media-inquiries', [MediaInquiryController::class, 'index'])->name('media-inquiries');
Route::post('/media-inquiries', [MediaInquiryController::class, 'validate'])->name('media-inquiries.validate');
Route::get('/pcs', fn () => view('frontend.coming-soon'))->name('pcs');
Route::get('/servers', fn () => view('frontend.coming-soon'))->name('servers');
Route::get('/laptops', fn () => view('frontend.coming-soon'))->name('laptops');
Route::get('/accessories', fn () => view('frontend.coming-soon'))->name('accessories');
Route::get('/it-tech-support-services-in-nj', fn () => view('frontend.coming-soon'))->name('it-tech-support-services-in-nj');
Route::get('/custom-built-solutions', fn () => view('frontend.coming-soon'))->name('custom-built-solutions');
Route::get('/nj-technical-relocation-services', fn () => view('frontend.coming-soon'))->name('nj-technical-relocation-services');
