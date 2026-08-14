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
use App\Http\Controllers\GiftCardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MediaResourceController;
use App\Http\Controllers\MediaVideoController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PressReleaseController;
use App\Http\Controllers\RadioShowController;
use App\Http\Controllers\RandomActsOfKindnessController;
use App\Http\Controllers\RecommendedController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ServicePageController;
use App\Http\Controllers\TestimonialController;
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
// CONTENT PAGES (ported from CI Home controller)
// ==============================
Route::get('/news', [NewsController::class, 'list'])->name('news');
Route::get('/news/index/{start}', [NewsController::class, 'list'])->name('news.index');
Route::get('/news/{link}', [NewsController::class, 'detail'])->name('news.detail');

Route::get('/events', [EventController::class, 'posts'])->name('events');
Route::get('/events/{link}', [EventController::class, 'detail'])->name('events.detail');

Route::get('/case-studies', [CaseStudyController::class, 'posts'])->name('case-studies');
Route::get('/case-studies/{link}', [CaseStudyController::class, 'detail'])->name('case-studies.detail');

Route::get('/media-resources', [MediaResourceController::class, 'posts'])->name('media-resources');
Route::get('/media-resources/{link}', [MediaResourceController::class, 'detail'])->name('media-resources.detail');

Route::get('/media-video', [MediaVideoController::class, 'posts'])->name('media-video');
Route::get('/media-video/{link}', [MediaVideoController::class, 'detail'])->name('media-video.detail');

Route::get('/press-releases', [PressReleaseController::class, 'posts'])->name('press-releases');
Route::get('/press-releases/{link}', [PressReleaseController::class, 'detail'])->name('press-releases.detail');

Route::get('/brand-guidelines', [BrandGuidelineController::class, 'posts'])->name('brand-guidelines');
Route::get('/brand-guidelines/{link}', [BrandGuidelineController::class, 'detail'])->name('brand-guidelines.detail');

Route::get('/recommended', [RecommendedController::class, 'posts'])->name('recommended');
Route::get('/recommended/{link}', [RecommendedController::class, 'detail'])->name('recommended.detail');

Route::get('/random-acts-of-kindness', [RandomActsOfKindnessController::class, 'posts'])->name('random-acts-of-kindness');
Route::get('/random-acts-of-kindness/{link}', [RandomActsOfKindnessController::class, 'detail'])->name('random-acts-of-kindness.detail');

Route::get('/jmor-shows', [RadioShowController::class, 'posts'])->name('jmor-shows');
Route::get('/jmor-shows/{link}', [RadioShowController::class, 'detail'])->name('jmor-shows.detail');
Route::get('/category-jmor-shows/{link}', [RadioShowController::class, 'category'])->name('category-jmor-shows');
Route::get('/category-jmor-shows/{link}/{year}', [RadioShowController::class, 'category'])->name('category-jmor-shows.year');
Route::post('/home/get_categories_list', [RadioShowController::class, 'categoriesList'])->name('radio.categories');

Route::get('/service', [ServicePageController::class, 'list'])->name('services');
Route::get('/service/{link}', [ServicePageController::class, 'detail'])->name('service.detail');

Route::get('/testimonials', [TestimonialController::class, 'index'])->name('testimonials');

Route::get('/gift-card', [GiftCardController::class, 'list'])->name('gift-card');

Route::get('/search', [SearchController::class, 'content'])->name('search');
Route::post('/search', [SearchController::class, 'content'])->name('search.submit');
Route::post('/search-shows', [SearchController::class, 'radio'])->name('search.radio');

// ==============================
// DB PAGES (rendered from the pages table, ported from CI's (:any) catch-all)
// ==============================
Route::get('/about-us', fn () => app(PageController::class)->show('about-us'))->name('about-us');
Route::get('/about', fn () => view('frontend.coming-soon'))->name('about');
Route::get('/our-mission', fn () => app(PageController::class)->show('our-mission'))->name('our-mission');
Route::get('/why-choose-jmor', fn () => app(PageController::class)->show('why-choose-jmor'))->name('why-choose-jmor');
Route::get('/refund-policy', fn () => app(PageController::class)->show('refund-policy'))->name('refund-policy');
Route::get('/privacy-policy', fn () => app(PageController::class)->show('privacy-policy'))->name('privacy-policy');
Route::get('/network-cyber-security-internet-safety', fn () => app(PageController::class)->show('network-cyber-security-internet-safety'))->name('network-cyber-security-internet-safety');
Route::get('/hardware-firewalls-and-utm-devices', fn () => app(PageController::class)->show('hardware-firewalls-and-utm-devices'))->name('hardware-firewalls-and-utm-devices');
Route::get('/technical-relocation-services', fn () => app(PageController::class)->show('technical-relocation-services'))->name('technical-relocation-services');
Route::get('/the-jmor-blog', fn () => app(PageController::class)->show('the-jmor-blog'))->name('the-jmor-blog');
Route::get('/attorneys-law-firms', fn () => app(PageController::class)->show('attorneys-law-firms'))->name('attorneys-law-firms');
Route::get('/cpa-firms', fn () => app(PageController::class)->show('cpa-firms'))->name('cpa-firms');
Route::get('/dentists', fn () => app(PageController::class)->show('dentists'))->name('dentists');
Route::get('/general-practice-doctors', fn () => app(PageController::class)->show('general-practice-doctors'))->name('general-practice-doctors');
Route::get('/fast-food-restaurants', fn () => app(PageController::class)->show('fast-food-restaurants'))->name('fast-food-restaurants');
Route::get('/manufacturers', fn () => app(PageController::class)->show('manufacturers'))->name('manufacturers');
Route::get('/office-managers', fn () => app(PageController::class)->show('office-managers'))->name('office-managers');

// ==============================
// REMAINING CONTENT PAGE STUBS (no page/service in the DB yet)
// ==============================
Route::get('/it-service-providers-in-new-jersey-for-homes-and-businesses', fn () => view('frontend.coming-soon'))->name('it-service-providers-in-new-jersey-for-homes-and-businesses');
Route::get('/antivirus-malware-ransomware-vunerability-endpoint-management-solutions', fn () => view('frontend.coming-soon'))->name('antivirus-malware-ransomware-vunerability-endpoint-management-solutions');
Route::get('/ai-solutions-for-businesses-consumers-safe-secure-ai-integrations-in-nj-and-beyond', fn () => view('frontend.coming-soon'))->name('ai-solutions-for-businesses-consumers-safe-secure-ai-integrations-in-nj-and-beyond');
Route::get('/our-dei-diversiety-equity-inclusion-non-discrimination-policy', fn () => view('frontend.coming-soon'))->name('our-dei-diversiety-equity-inclusion-non-discrimination-policy');
Route::get('/media-relations', fn () => view('frontend.coming-soon'))->name('media-relations');
Route::get('/technology-guides-it-resources-the-jmor-connection-inc', fn () => view('frontend.coming-soon'))->name('technology-guides-it-resources-the-jmor-connection-inc');
Route::get('/the-jmor-store', fn () => view('frontend.coming-soon'))->name('the-jmor-store');
Route::get('/terms', fn () => view('frontend.coming-soon'))->name('terms');
Route::get('/sitemap', fn () => view('frontend.coming-soon'))->name('sitemap');
Route::get('/solutions', fn () => view('frontend.coming-soon'))->name('solutions');

// ==============================
// DB MENU LINK STUBS (extra menu items surfaced in the megamenu)
// ==============================
Route::get('/we-serve', fn () => app(PageController::class)->show('we-serve'))->name('we-serve');
Route::get('/custom-built-technology-solutions-in-nj', fn () => app(PageController::class)->show('custom-built-technology-solutions-in-nj'))->name('custom-built-technology-solutions-in-nj');
Route::get('/covid19-remote-friendly-support-service', fn () => app(PageController::class)->show('covid19-remote-friendly-support-service'))->name('covid19-remote-friendly-support-service');
Route::get('/it-tech-support-services-in-nj', fn () => app(PageController::class)->show('it-tech-support-services-in-nj'))->name('it-tech-support-services-in-nj');
Route::get('/custom-built-solutions', fn () => app(PageController::class)->show('custom-built-solutions'))->name('custom-built-solutions');
Route::get('/nj-technical-relocation-services', fn () => app(PageController::class)->show('nj-technical-relocation-services'))->name('nj-technical-relocation-services');

Route::get('/social', fn () => view('frontend.coming-soon'))->name('social');
Route::get('/request-information', fn () => view('frontend.coming-soon'))->name('request-information');
Route::get('/it-support-solutions-for-new-jersey-businesses-and-homes-the-jmor-connection', fn () => view('frontend.coming-soon'))->name('it-support-solutions-for-new-jersey-businesses-and-homes-the-jmor-connection');
Route::get('/print-management-solution', fn () => view('frontend.coming-soon'))->name('print-management-solution');
Route::get('/remote-employee-engagement-solutions', fn () => view('frontend.coming-soon'))->name('remote-employee-engagement-solutions');
Route::get('/linktree', fn () => view('frontend.coming-soon'))->name('linktree');
Route::get('/jmor-tech-byte-sized-blunders-laugh-and-learn', fn () => view('frontend.coming-soon'))->name('jmor-tech-byte-sized-blunders-laugh-and-learn');
Route::get('/jmor-tech-talk-show', fn () => view('frontend.coming-soon'))->name('jmor-tech-talk-show');
Route::get('/media-inquiries', fn () => view('frontend.coming-soon'))->name('media-inquiries');
Route::get('/pcs', fn () => view('frontend.coming-soon'))->name('pcs');
Route::get('/servers', fn () => view('frontend.coming-soon'))->name('servers');
Route::get('/laptops', fn () => view('frontend.coming-soon'))->name('laptops');
Route::get('/accessories', fn () => view('frontend.coming-soon'))->name('accessories');

// ==============================
// BLOG (ported from CI: home/blog_posts + home/blog_detail + blog_legacy_redirect)
// ==============================
Route::get('/blog', [BlogController::class, 'posts'])->name('blog');
Route::get('/blog/blog/{link}', fn ($link) => redirect()->route('blog.detail', $link, 301))->name('blog.legacy_redirect');
Route::get('/blog/{link}', [BlogController::class, 'detail'])->name('blog.detail');

// ==============================
// DB PAGES CATCH-ALL (ports CI's `$route['(:any)'] = home/pages` fallback)
// ==============================
Route::get('/{pageLink}', [PageController::class, 'show'])->name('pages.show');
