<?php

use App\Models\MarketingService;
use App\Models\TalkShowSetting;
use App\Services\PaymentService;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Mockery\MockInterface;

beforeEach(function () {
    $this->withoutMiddleware(PreventRequestForgery::class);

    TalkShowSetting::query()->delete();
    MarketingService::query()->delete();

    TalkShowSetting::create(['price' => '450.00', 'question' => 'What is it question ?']);
    MarketingService::create([
        'name' => 'TLT',
        'question' => 'Why TLT?',
        'product_code' => '100909',
        'description' => 'TLT Services',
        'price' => '1.00',
    ]);

    $this->validInput = [
        'name' => 'Guest Name',
        'email' => 'guest@example.com',
        'services' => ['100909'],
        'ans' => ['Because TLT'],
        'number' => '4111111111111111',
        'expiry' => '12/28',
        'cvc' => '123',
    ];
});

test('the guest application page shows the order summary', function () {
    $this->get(route('talk-show.checkout'))
        ->assertOk()
        ->assertSee('ORDER SUMMARY')
        ->assertSee('450.00')
        ->assertSee('Marketing Services')
        ->assertSee('TLT')
        ->assertSee('Why TLT?')
        ->assertSee('Card Number');
});

test('contact and card fields are required', function () {
    $this->post(route('talk-show.charge'), [])
        ->assertSessionHasErrors(['name', 'email', 'number', 'expiry', 'cvc']);
});

test('an invalid card number is rejected', function () {
    $input = $this->validInput;
    $input['number'] = 'not-a-card';

    $this->post(route('talk-show.charge'), $input)
        ->assertSessionHasErrors('number');
});

test('a successful payment redirects back with a confirmation', function () {
    $this->mock(PaymentService::class, function (MockInterface $mock) {
        $mock->shouldReceive('chargeGuestApplication')
            ->once()
            ->andReturn(true);
    });

    $this->post(route('talk-show.charge'), $this->validInput)
        ->assertRedirect(route('talk-show.checkout'))
        ->assertSessionHas('added');
});

test('a failed payment reports an error', function () {
    $this->mock(PaymentService::class, function (MockInterface $mock) {
        $mock->shouldReceive('chargeGuestApplication')
            ->once()
            ->andReturn(false);
    });

    $this->post(route('talk-show.charge'), $this->validInput)
        ->assertRedirect(route('talk-show.checkout'))
        ->assertSessionHas('payment_failed');
});
