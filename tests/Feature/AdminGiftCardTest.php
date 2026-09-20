<?php

use App\Filament\Admin\Resources\GiftCardResource\Pages\CreateGiftCard;
use App\Filament\Admin\Resources\GiftCardResource\Pages\EditGiftCard;
use App\Models\Admin;
use App\Models\GiftCard;
use Filament\Actions\DeleteAction;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;

beforeEach(function () {
    $this->withoutMiddleware(PreventRequestForgery::class);

    $this->admin = Admin::create([
        'firstname' => 'Test',
        'lastname' => 'Admin',
        'email' => 'admin@test.com',
        'password' => bcrypt('password'),
        'status' => 1,
        'image' => '',
        'last_login' => now(),
        'date_register' => now(),
    ]);

    $this->actingAs($this->admin, 'admin');
});

test('admin can access gift card list page', function () {
    $this->get('/admin/gift-cards')->assertSuccessful();
});

test('gift card list page shows existing records', function () {
    GiftCard::create([
        'name' => 'Test Gift Card',
        'heading' => '$50 Gift Card',
        'price' => '50',
        'upfront' => '50',
        'coupon_number' => 'ABC123',
        'status' => 1,
        'link' => 'test-gift-card',
        'description' => '',
        'image' => '',
        'category' => '',
    ]);

    $this->get('/admin/gift-cards')->assertSuccessful();
});

test('admin can access create gift card page', function () {
    $this->get('/admin/gift-cards/create')->assertSuccessful();
});

test('admin can create a gift card', function () {
    Livewire::test(CreateGiftCard::class)
        ->fillForm([
            'name' => 'New Gift Card',
            'heading' => '$100 Gift Card',
            'description' => 'A great gift card',
            'price' => '100',
            'upfront' => '100',
            'category' => 'premium',
            'coupon_number' => 'XYZ789',
            'status' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('gift_card', [
        'name' => 'New Gift Card',
        'price' => '100',
        'coupon_number' => 'XYZ789',
    ]);
});

test('admin can edit a gift card', function () {
    $giftCard = GiftCard::create([
        'name' => 'Edit Gift Card',
        'heading' => '$50 Gift Card',
        'price' => '50',
        'upfront' => '50',
        'coupon_number' => 'EDIT001',
        'status' => 0,
        'link' => 'edit-gift-card',
        'description' => '',
        'image' => '',
        'category' => '',
    ]);

    Livewire::test(EditGiftCard::class, ['record' => $giftCard->id])
        ->fillForm([
            'name' => 'Updated Gift Card',
            'heading' => '$75 Gift Card',
            'price' => '75',
            'upfront' => '75',
            'coupon_number' => 'EDIT001',
            'status' => true,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('gift_card', [
        'id' => $giftCard->id,
        'name' => 'Updated Gift Card',
        'price' => '75',
    ]);
});

test('admin can delete a gift card', function () {
    $giftCard = GiftCard::create([
        'name' => 'Delete Gift Card',
        'heading' => '$25 Gift Card',
        'price' => '25',
        'upfront' => '25',
        'coupon_number' => 'DEL001',
        'status' => 0,
        'link' => 'delete-gift-card',
        'description' => '',
        'image' => '',
        'category' => '',
    ]);

    Livewire::test(EditGiftCard::class, ['record' => $giftCard->id])
        ->callAction(DeleteAction::class);

    $this->assertDatabaseMissing('gift_card', ['id' => $giftCard->id]);
});

test('gift card requires name', function () {
    Livewire::test(CreateGiftCard::class)
        ->fillForm([
            'name' => '',
            'heading' => '$50 Gift Card',
            'price' => '50',
            'upfront' => '50',
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required']);
});

test('gift card requires price', function () {
    Livewire::test(CreateGiftCard::class)
        ->fillForm([
            'name' => 'No Price Card',
            'heading' => '$50 Gift Card',
            'price' => '',
            'upfront' => '50',
        ])
        ->call('create')
        ->assertHasFormErrors(['price' => 'required']);
});

test('coupon number must be unique', function () {
    GiftCard::create([
        'name' => 'Existing Card',
        'heading' => '$50 Gift Card',
        'price' => '50',
        'upfront' => '50',
        'coupon_number' => 'UNIQUE001',
        'status' => 0,
        'link' => 'existing-card',
        'description' => '',
        'image' => '',
        'category' => '',
    ]);

    Livewire::test(CreateGiftCard::class)
        ->fillForm([
            'name' => 'Duplicate Card',
            'heading' => '$50 Gift Card',
            'price' => '50',
            'upfront' => '50',
            'coupon_number' => 'UNIQUE001',
        ])
        ->call('create')
        ->assertHasFormErrors(['coupon_number' => 'unique']);
});
