<?php

use App\Filament\Admin\Resources\CheckoutFormResource\Pages\CreateCheckoutForm;
use App\Filament\Admin\Resources\CheckoutFormResource\Pages\EditCheckoutForm;
use App\Filament\Admin\Resources\CheckoutFormResource\Pages\ListCheckoutForms;
use App\Models\Admin;
use App\Models\CheckoutMeta;
use App\Models\Package;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Livewire\Livewire;

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

    $this->package = Package::create([
        'link' => '/packages/test',
        'name' => 'Test Package',
        'priority' => 1,
        'heading' => 'Test Heading',
        'description' => 'Test description',
        'image' => '',
        'discount' => 0,
        'price' => '10.00',
        'upfront' => '0.00',
        'category_name' => '',
    ]);
});

test('checkout forms page renders', function () {
    $this->get('/admin/checkout-forms')->assertSuccessful();
});

test('admin can create a checkout form with customer and system fields', function () {
    Livewire::test(CreateCheckoutForm::class)
        ->fillForm([
            'form_name' => 'Client Details',
            'package_id' => $this->package->id,
            'formFields' => [
                [
                    'label' => 'Full Name',
                    'name' => 'full_name',
                    'types' => 1,
                    'required' => 1,
                    'placeholder' => 'Your name',
                ],
                [
                    'label' => 'Notes',
                    'name' => 'notes',
                    'types' => 2,
                    'required' => 2,
                    'placeholder' => 'Anything else',
                ],
            ],
            'systemFields' => [
                [
                    's_label' => 'Server',
                    's_name' => 'server',
                    's_types' => 1,
                    's_required' => 1,
                    's_placeholder' => 'Server name',
                ],
            ],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $meta = CheckoutMeta::where('form_name', 'Client Details')->first();

    expect($meta)->not->toBeNull()
        ->and((int) $meta->package_id)->toBe($this->package->id);

    $this->assertDatabaseHas('checkout_form', ['form_id' => $meta->id, 'name' => 'full_name', 'types' => 1]);
    $this->assertDatabaseHas('checkout_form', ['form_id' => $meta->id, 'name' => 'notes', 'types' => 2]);
    $this->assertDatabaseHas('system_information', ['form_id' => $meta->id, 's_name' => 'server']);
    $this->assertDatabaseCount('checkout_form', 2);
    $this->assertDatabaseCount('system_information', 1);
});

test('name is required on a checkout form', function () {
    Livewire::test(CreateCheckoutForm::class)
        ->fillForm([
            'form_name' => '',
            'package_id' => $this->package->id,
        ])
        ->call('create')
        ->assertHasErrors(['data.form_name']);
});

test('editing a checkout form replaces its fields', function () {
    $meta = CheckoutMeta::create(['form_name' => 'Old Form', 'package_id' => $this->package->id]);
    $meta->formFields()->create([
        'label' => 'Old Label', 'name' => 'old_field', 'types' => 1, 'required' => 1, 'placeholder' => '',
    ]);
    $meta->systemFields()->create([
        's_label' => 'Old Sys', 's_name' => 'old_sys', 's_types' => 1, 's_required' => 1, 's_placeholder' => '',
    ]);

    Livewire::test(EditCheckoutForm::class, ['record' => $meta->id])
        ->fillForm([
            'form_name' => 'Old Form',
            'package_id' => $this->package->id,
            'formFields' => [
                [
                    'label' => 'New Label',
                    'name' => 'new_field',
                    'types' => 2,
                    'required' => 2,
                    'placeholder' => 'x',
                ],
            ],
            'systemFields' => [],
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('checkout_form', ['form_id' => $meta->id, 'name' => 'new_field']);
    $this->assertDatabaseMissing('checkout_form', ['form_id' => $meta->id, 'name' => 'old_field']);
    $this->assertDatabaseMissing('system_information', ['form_id' => $meta->id, 's_name' => 'old_sys']);
    $this->assertDatabaseCount('checkout_form', 1);
});

test('admin can delete a checkout form and its fields', function () {
    $meta = CheckoutMeta::create(['form_name' => 'Delete Me', 'package_id' => $this->package->id]);
    $meta->formFields()->create([
        'label' => 'L', 'name' => 'field_a', 'types' => 1, 'required' => 1, 'placeholder' => '',
    ]);
    $meta->systemFields()->create([
        's_label' => 'SL', 's_name' => 'sys_a', 's_types' => 1, 's_required' => 1, 's_placeholder' => '',
    ]);

    Livewire::test(ListCheckoutForms::class)
        ->callTableAction('delete', $meta);

    $this->assertDatabaseMissing('checkout_meta', ['id' => $meta->id]);
    $this->assertDatabaseMissing('checkout_form', ['form_id' => $meta->id]);
    $this->assertDatabaseMissing('system_information', ['form_id' => $meta->id]);
});
