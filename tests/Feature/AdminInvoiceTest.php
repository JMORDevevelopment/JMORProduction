<?php

use App\Models\Admin;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;

beforeEach(function () {
    $this->withoutMiddleware(PreventRequestForgery::class);

    $this->admin = Admin::create([
        'firstname' => 'Test',
        'lastname' => 'Admin',
        'email' => 'admin@test.com',
        'password' => bcrypt('password'),
        'status' => 1,
        'role' => 1,
        'image' => '',
        'last_login' => now(),
        'date_register' => now(),
    ]);

    $this->admin->forceFill([
        'two_factor_secret' => encrypt('test-two-factor-secret'),
        'two_factor_confirmed_at' => now(),
    ])->save();
    $this->admin->setTwoFactorChallengePassed();

    $this->actingAs($this->admin, 'admin');
});

function makeInvoiceTransaction(array $overrides = []): Transaction
{
    return Transaction::create(array_merge([
        'order_id' => 501,
        'order_type' => 'Package',
        'checkout_type' => 'Monthly',
        'transaction_id' => 'TXN12345',
        'auth_code' => 'AUTH001',
        'user_id' => 1,
        'amount' => 29.99,
    ], $overrides));
}

test('admin can print an order invoice', function () {
    $user = User::create([
        'firstname' => 'Jane',
        'lastname' => 'Doe',
        'email' => 'jane@example.com',
        'password' => bcrypt('password'),
        'date_added' => now(),
    ]);

    Order::forceCreate([
        'id' => 501,
        'user_id' => $user->user_id,
        'sub_total' => 29.99,
        'grand_total' => 29.99,
        'checkout_data' => json_encode(['domain' => 'example.com']),
    ]);

    makeInvoiceTransaction(['user_id' => $user->user_id]);

    $this->get(route('admin.order_invoice', 501))
        ->assertSuccessful()
        ->assertSee('INV-501')
        ->assertSee('Jane Doe');
});

test('order invoice 404s when no matching transaction exists', function () {
    $this->get(route('admin.order_invoice', 999))->assertNotFound();
});

test('gift card orders use the gift card invoice route', function () {
    $user = User::create([
        'firstname' => 'Gift',
        'lastname' => 'Buyer',
        'email' => 'gift@example.com',
        'password' => bcrypt('password'),
        'date_added' => now(),
    ]);

    makeInvoiceTransaction(['order_id' => 502, 'order_type' => 'Gift Card', 'user_id' => $user->user_id]);

    $this->get(route('admin.order_invoice', 502))->assertNotFound();

    $this->get(route('admin.giftcard_invoice', 502))
        ->assertSuccessful()
        ->assertSee('Gift Buyer');
});

test('guests are redirected away from admin invoices', function () {
    makeInvoiceTransaction();

    auth('admin')->logout();

    $this->get(route('admin.order_invoice', 501))->assertRedirect();
});

test('customer invoice page still renders after blade extraction', function () {
    $user = User::create([
        'firstname' => 'Reg',
        'lastname' => 'Ular',
        'email' => 'regular@example.com',
        'password' => bcrypt('password'),
        'date_added' => now(),
    ]);

    makeInvoiceTransaction(['order_id' => 503, 'user_id' => $user->user_id]);

    $this->actingAs($user, 'web');

    $this->get(route('dashboard.order_invoice', 503))
        ->assertSuccessful()
        ->assertSee('INV-503');
});
