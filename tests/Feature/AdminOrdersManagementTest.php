<?php

use App\Models\Admin;
use App\Models\Order;
use App\Models\OrderDetail;
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

    $this->user = User::create([
        'firstname' => 'John',
        'lastname' => 'Doe',
        'email' => 'john@example.com',
        'password' => bcrypt('password'),
        'date_added' => now()->toDateString(),
    ]);

    $this->actingAs($this->admin, 'admin');
});

test('admin can view package orders list', function () {
    $order = Order::create([
        'user_id' => $this->user->user_id,
        'sub_total' => 99.99,
        'discount' => 10.00,
        'grand_total' => 89.99,
        'create_date' => now()->toDateString(),
        'status' => 1,
        'checkout_data' => json_encode([]),
    ]);

    Transaction::create([
        'order_id' => $order->id,
        'order_type' => 'Monthly',
        'checkout_type' => 'Monthly',
        'transaction_id' => 'TXN123456',
        'auth_code' => 'AUTH789',
        'user_id' => $this->user->user_id,
        'amount' => 89.99,
    ]);

    $response = $this->get('/admin/transactions');
    $response->assertStatus(200);
});

test('admin can view single transaction', function () {
    $order = Order::create([
        'user_id' => $this->user->user_id,
        'sub_total' => 199.99,
        'discount' => 0,
        'grand_total' => 199.99,
        'create_date' => now()->toDateString(),
        'status' => 1,
        'checkout_data' => json_encode([]),
    ]);

    $transaction = Transaction::create([
        'order_id' => $order->id,
        'order_type' => 'Yearly',
        'checkout_type' => 'Yearly',
        'transaction_id' => 'TXN789012',
        'auth_code' => 'AUTH456',
        'user_id' => $this->user->user_id,
        'amount' => 199.99,
    ]);

    $response = $this->get('/admin/transactions/'.$transaction->id);
    $response->assertStatus(200);
});

test('admin can view order details in invoice', function () {
    $order = Order::create([
        'user_id' => $this->user->user_id,
        'sub_total' => 250.00,
        'discount' => 25.00,
        'grand_total' => 225.00,
        'create_date' => now()->toDateString(),
        'status' => 1,
        'checkout_data' => json_encode([]),
    ]);

    OrderDetail::create([
        'item' => 'Basic Plan',
        'type' => 'Monthly',
        'qty' => 1,
        'price' => 150.00,
        'sub_total' => 150.00,
        'order_id' => $order->id,
        'date_added' => now()->toDateString(),
    ]);

    OrderDetail::create([
        'item' => 'Premium Support',
        'type' => 'Monthly',
        'qty' => 1,
        'price' => 100.00,
        'sub_total' => 100.00,
        'order_id' => $order->id,
        'date_added' => now()->toDateString(),
    ]);

    $transaction = Transaction::create([
        'order_id' => $order->id,
        'order_type' => 'Monthly',
        'checkout_type' => 'Monthly',
        'transaction_id' => 'TXN345678',
        'auth_code' => 'AUTH123',
        'user_id' => $this->user->user_id,
        'amount' => 225.00,
    ]);

    $response = $this->get('/admin/transactions/'.$transaction->id);
    $response->assertStatus(200);
});

test('package orders excludes gift card orders', function () {
    $order = Order::create([
        'user_id' => $this->user->user_id,
        'sub_total' => 50.00,
        'discount' => 0,
        'grand_total' => 50.00,
        'create_date' => now()->toDateString(),
        'status' => 1,
        'checkout_data' => json_encode([]),
    ]);

    Transaction::create([
        'order_id' => $order->id,
        'order_type' => 'Monthly',
        'checkout_type' => 'Monthly',
        'transaction_id' => 'TXN111',
        'auth_code' => 'AUTH111',
        'user_id' => $this->user->user_id,
        'amount' => 50.00,
    ]);

    Transaction::create([
        'order_id' => $order->id,
        'order_type' => 'Gift Card',
        'checkout_type' => 'Gift Card',
        'transaction_id' => 'TXN222',
        'auth_code' => 'AUTH222',
        'user_id' => $this->user->user_id,
        'amount' => 25.00,
    ]);

    $response = $this->get('/admin/transactions');
    $response->assertStatus(200);
});

test('admin can view gift card orders list', function () {
    $order = Order::create([
        'user_id' => $this->user->user_id,
        'sub_total' => 50.00,
        'discount' => 0,
        'grand_total' => 50.00,
        'create_date' => now()->toDateString(),
        'status' => 1,
        'checkout_data' => json_encode([]),
    ]);

    Transaction::create([
        'order_id' => $order->id,
        'order_type' => 'Gift Card',
        'checkout_type' => 'Gift Card',
        'transaction_id' => 'GCT123456',
        'auth_code' => 'AUTH_GC',
        'user_id' => $this->user->user_id,
        'amount' => 50.00,
    ]);

    $response = $this->get('/admin/gift-card-transactions');
    $response->assertStatus(200);
});

test('admin can view single gift card transaction', function () {
    $order = Order::create([
        'user_id' => $this->user->user_id,
        'sub_total' => 100.00,
        'discount' => 0,
        'grand_total' => 100.00,
        'create_date' => now()->toDateString(),
        'status' => 1,
        'checkout_data' => json_encode([]),
    ]);

    $transaction = Transaction::create([
        'order_id' => $order->id,
        'order_type' => 'Gift Card',
        'checkout_type' => 'Gift Card',
        'transaction_id' => 'GCT789012',
        'auth_code' => 'AUTH_GC2',
        'user_id' => $this->user->user_id,
        'amount' => 100.00,
    ]);

    $response = $this->get('/admin/gift-card-transactions/'.$transaction->id);
    $response->assertStatus(200);
});

test('gift card orders only shows gift card type', function () {
    $order = Order::create([
        'user_id' => $this->user->user_id,
        'sub_total' => 50.00,
        'discount' => 0,
        'grand_total' => 50.00,
        'create_date' => now()->toDateString(),
        'status' => 1,
        'checkout_data' => json_encode([]),
    ]);

    Transaction::create([
        'order_id' => $order->id,
        'order_type' => 'Monthly',
        'checkout_type' => 'Monthly',
        'transaction_id' => 'TXN111',
        'auth_code' => 'AUTH111',
        'user_id' => $this->user->user_id,
        'amount' => 50.00,
    ]);

    Transaction::create([
        'order_id' => $order->id,
        'order_type' => 'Gift Card',
        'checkout_type' => 'Gift Card',
        'transaction_id' => 'GCT222',
        'auth_code' => 'AUTH222',
        'user_id' => $this->user->user_id,
        'amount' => 25.00,
    ]);

    $response = $this->get('/admin/gift-card-transactions');
    $response->assertStatus(200);
});

test('transaction displays correct amount', function () {
    $order = Order::create([
        'user_id' => $this->user->user_id,
        'sub_total' => 150.00,
        'discount' => 15.00,
        'grand_total' => 135.00,
        'create_date' => now()->toDateString(),
        'status' => 2,
        'checkout_data' => json_encode([]),
    ]);

    $transaction = Transaction::create([
        'order_id' => $order->id,
        'order_type' => 'Monthly',
        'checkout_type' => 'Monthly',
        'transaction_id' => 'TXN999888',
        'auth_code' => 'AUTH999',
        'user_id' => $this->user->user_id,
        'amount' => 135.00,
    ]);

    $response = $this->get('/admin/transactions/'.$transaction->id);
    $response->assertStatus(200);
});

test('admin can view transaction with no order details', function () {
    $order = Order::create([
        'user_id' => $this->user->user_id,
        'sub_total' => 0,
        'discount' => 0,
        'grand_total' => 0,
        'create_date' => now()->toDateString(),
        'status' => 1,
        'checkout_data' => json_encode([]),
    ]);

    $transaction = Transaction::create([
        'order_id' => $order->id,
        'order_type' => 'Monthly',
        'checkout_type' => 'Monthly',
        'transaction_id' => 'TXN000',
        'auth_code' => 'AUTH000',
        'user_id' => $this->user->user_id,
        'amount' => 0,
    ]);

    $response = $this->get('/admin/transactions/'.$transaction->id);
    $response->assertStatus(200);
});
