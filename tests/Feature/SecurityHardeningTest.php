<?php

use App\Filament\Admin\Resources\PageResource\Pages\CreatePage;
use App\Models\CouponCheckout;
use App\Models\GiftCard;
use App\Services\CartService;
use App\Support\RichText;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;

beforeEach(function () {
    $this->withoutMiddleware(PreventRequestForgery::class);
});

test('every response carries the baseline security headers', function () {
    $response = $this->get('/');

    $response->assertSuccessful();
    $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    $response->assertHeader('X-Content-Type-Options', 'nosniff');
    $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    $response->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()');
});

test('rich text sanitizer keeps safe markup but strips scripts and handlers', function () {
    $dirty = '<p>Hello <strong>world</strong><script>alert(1)</script>'
        .'<img src=x onerror=alert(1)><a href="javascript:alert(1)">x</a></p>';

    $clean = (string) RichText::sanitize($dirty);

    expect($clean)->toContain('<p>');
    expect($clean)->toContain('<strong>world</strong>');
    expect($clean)->not->toContain('<script');
    expect($clean)->not->toContain('onerror');
    expect($clean)->not->toContain('javascript:');
});

test('rich text sanitizer preserves legacy rich content and tolerates null', function () {
    expect((string) RichText::sanitize('<p>Plan <br><em>details</em></p>'))->toContain('<em>details</em>');
    expect((string) RichText::sanitize(null))->toBe('');
});

test('gift card codes that do not exist are rejected before entering the session', function () {
    $cart = app(CartService::class);

    expect($cart->applyCoupon('DOES-NOT-EXIST'))->toBeFalse();
    expect(session()->has('coupon_code'))->toBeFalse();
});

test('a real gift card code is accepted into the session', function () {
    CouponCheckout::create([
        'gift_card_id' => 0,
        'order_id' => 0,
        'coupon_number' => 'TESTCARD99',
        'status' => 0,
    ]);

    expect(app(CartService::class)->applyCoupon('TESTCARD99'))->toBeTrue();
    expect(session()->get('coupon_code'))->toBe('TESTCARD99');
});

test('a redeemed gift card code is rejected and leaves the session untouched', function () {
    CouponCheckout::create([
        'gift_card_id' => 0,
        'order_id' => 1,
        'coupon_number' => 'REDEEMED1',
        'status' => 1,
    ]);

    expect(app(CartService::class)->applyCoupon('REDEEMED1'))->toBeFalse();
    expect(session()->has('coupon_code'))->toBeFalse();
    expect(session()->has('discount_value'))->toBeFalse();
});

test('applying a gift card stores its price as the checkout discount', function () {
    $giftCard = GiftCard::create([
        'link' => 'test-gift-card',
        'name' => 'Test Gift Card',
        'heading' => '$25 Gift Card',
        'description' => 'desc',
        'image' => 'uploads/gift_card/test.png',
        'price' => '25.00',
        'upfront' => '25.00',
        'category' => 'Standard',
        'status' => 0,
    ]);

    CouponCheckout::create([
        'gift_card_id' => $giftCard->id,
        'order_id' => 0,
        'coupon_number' => 'DISCOUNT25',
        'status' => 0,
    ]);

    expect(app(CartService::class)->applyCoupon('DISCOUNT25'))->toBeTrue();
    expect((float) session()->get('discount_value'))->toBe(25.0);
});

test('order placement routes are no longer reachable via GET', function () {
    $this->get('/home/placeOrder')->assertStatus(405);
    $this->get('/home/placeOrderGiftcard')->assertStatus(405);
    $this->get('/cart/removeItem/1')->assertStatus(405);
});

test('a page cannot be created with a slug that already exists', function () {
    $createPage = fn () => Livewire::test(CreatePage::class)
        ->set('data.name', 'Duplicate Slug Page')
        ->set('data.description', '<p>Content</p>')
        ->set('data.image', ['uploads/pages/test.jpg']);

    $createPage()->call('create')->assertHasNoFormErrors();

    $this->assertDatabaseHas('pages', ['link' => 'duplicate-slug-page']);

    $createPage()->call('create')->assertHasErrors(['data.name']);
});

test('contact submissions are rate limited after five attempts', function () {
    $payload = [
        'name' => 'Throttle Tester',
        'email' => 'throttle@example.com',
        'phone' => '555-0000',
        'reason' => 'General Inquiry',
        'message' => 'Hello there',
        'firstNumber' => 1,
        'secondNumber' => 1,
        'protection_question' => 2,
    ];

    for ($attempt = 0; $attempt < 5; $attempt++) {
        $this->post(route('contact.submit'), $payload);
    }

    $this->post(route('contact.submit'), $payload)->assertStatus(429);
});
