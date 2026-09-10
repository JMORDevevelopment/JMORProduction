<?php

use App\Filament\Admin\Resources\CategoryResource\Pages\CreateCategory;
use App\Filament\Admin\Resources\CategoryResource\Pages\EditCategory;
use App\Filament\Admin\Resources\CategoryResource\Pages\ListCategories;
use App\Filament\Admin\Resources\PackageResource\Pages\CreatePackage;
use App\Filament\Admin\Resources\PackageResource\Pages\EditPackage;
use App\Filament\Admin\Resources\PackageResource\Pages\ListPackages;
use App\Models\Admin;
use App\Models\Category;
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
        'image' => '',
        'last_login' => now(),
        'date_register' => now(),
    ]);

    $this->actingAs($this->admin, 'admin');

    // Seed categories for Select validation
    Category::create(['name' => 'Home', 'link' => 'home', 'priority' => 1, 'menu_status' => 1]);
    Category::create(['name' => 'Business', 'link' => 'business', 'priority' => 2, 'menu_status' => 1]);
});

// ─── Category Tests ───

it('can render category list page', function () {
    Livewire::test(ListCategories::class)
        ->assertSuccessful();
});

it('can render category create page', function () {
    Livewire::test(CreateCategory::class)
        ->assertSuccessful();
});

it('can create a category', function () {
    Livewire::test(CreateCategory::class)
        ->set('data.name', 'Enterprise')
        ->set('data.link', 'enterprise')
        ->set('data.priority', 3)
        ->set('data.menu_status', true)
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('category', [
        'name' => 'Enterprise',
        'link' => 'enterprise',
        'priority' => 3,
        'menu_status' => 1,
    ]);
});

it('can render category edit page', function () {
    $category = Category::create([
        'name' => 'New Cat',
        'link' => 'new-cat',
        'priority' => 1,
        'menu_status' => 0,
    ]);

    Livewire::test(EditCategory::class, ['record' => $category->category_id])
        ->assertSuccessful();
});

it('can update a category', function () {
    $category = Category::create([
        'name' => 'To Update',
        'link' => 'to-update',
        'priority' => 1,
        'menu_status' => 0,
    ]);

    Livewire::test(EditCategory::class, ['record' => $category->category_id])
        ->set('data.name', 'Updated')
        ->set('data.link', 'updated')
        ->set('data.priority', 5)
        ->set('data.menu_status', true)
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('category', [
        'name' => 'Updated',
        'link' => 'updated',
        'priority' => 5,
        'menu_status' => 1,
    ]);
});

it('validates category name is required', function () {
    Livewire::test(CreateCategory::class)
        ->set('data.name', '')
        ->set('data.link', 'test')
        ->call('create')
        ->assertHasFormErrors(['name' => 'required']);
});

it('validates category link is required', function () {
    Livewire::test(CreateCategory::class)
        ->set('data.name', 'Test')
        ->set('data.link', '')
        ->call('create')
        ->assertHasFormErrors(['link' => 'required']);
});

it('validates category link is unique', function () {
    Livewire::test(CreateCategory::class)
        ->set('data.name', 'Home 2')
        ->set('data.link', 'home')
        ->call('create')
        ->assertHasFormErrors(['link' => 'unique']);
});

// ─── Package Tests ───

it('can render package list page', function () {
    Livewire::test(ListPackages::class)
        ->assertSuccessful();
});

it('can render package create page', function () {
    Livewire::test(CreatePackage::class)
        ->assertSuccessful();
});

it('can create a package', function () {
    Livewire::test(CreatePackage::class)
        ->set('data.name', 'Test Package')
        ->set('data.heading', 'Test Package Full Title')
        ->set('data.priority', 1)
        ->set('data.discount', 10)
        ->set('data.category_name', 'home')
        ->set('data.status', true)
        ->set('data.description', 'Test description')
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('packages', [
        'name' => 'Test Package',
        'heading' => 'Test Package Full Title',
        'category_name' => 'home',
        'discount' => 10,
        'status' => 1,
    ]);
});

it('can create a package with server prices', function () {
    Livewire::test(CreatePackage::class)
        ->set('data.name', 'Package With Prices')
        ->set('data.heading', 'Package With Prices Full')
        ->set('data.priority', 1)
        ->set('data.discount', 10)
        ->set('data.category_name', 'home')
        ->set('data.status', true)
        ->set('data.serverPrices', [
            ['pack_price' => 100, 'from_qty' => 1, 'to_qty' => 5],
            ['pack_price' => 200, 'from_qty' => 5, 'to_qty' => 10],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $package = Package::where('name', 'Package With Prices')->first();
    $this->assertNotNull($package);
    $this->assertCount(2, $package->serverPrices);
});

it('can create a package with system prices', function () {
    Livewire::test(CreatePackage::class)
        ->set('data.name', 'Package With System Prices')
        ->set('data.heading', 'Full Title')
        ->set('data.priority', 1)
        ->set('data.discount', 10)
        ->set('data.category_name', 'home')
        ->set('data.status', true)
        ->set('data.systemPrices', [
            ['system_price' => 99, 'from_qty' => 1, 'to_qty' => 9],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $package = Package::where('name', 'Package With System Prices')->first();
    $this->assertNotNull($package);
    $this->assertCount(1, $package->systemPrices);
});

it('can render package edit page', function () {
    $package = Package::create([
        'name' => 'Existing Package',
        'heading' => 'Existing Package Full',
        'link' => 'existing-package',
        'priority' => 1,
        'discount' => 10,
        'category_name' => 'home',
        'status' => 1,
        'description' => 'Desc',
        'image' => '',
        'price' => '0',
        'upfront' => '0',
    ]);

    Livewire::test(EditPackage::class, ['record' => $package->id])
        ->assertSuccessful();
});

it('can update a package', function () {
    $package = Package::create([
        'name' => 'Old Name',
        'heading' => 'Old Heading',
        'link' => 'old-name',
        'priority' => 1,
        'discount' => 5,
        'category_name' => 'home',
        'status' => 1,
        'description' => 'Old desc',
        'image' => '',
        'price' => '0',
        'upfront' => '0',
    ]);

    Livewire::test(EditPackage::class, ['record' => $package->id])
        ->set('data.name', 'New Name')
        ->set('data.heading', 'New Heading')
        ->set('data.priority', 5)
        ->set('data.discount', 15)
        ->set('data.category_name', 'business')
        ->set('data.status', false)
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('packages', [
        'name' => 'New Name',
        'heading' => 'New Heading',
        'priority' => 5,
        'discount' => 15,
        'category_name' => 'business',
        'status' => 0,
    ]);
});

it('validates package name is required', function () {
    Livewire::test(CreatePackage::class)
        ->set('data.name', '')
        ->set('data.heading', 'Test')
        ->set('data.category_name', 'home')
        ->call('create')
        ->assertHasFormErrors(['name' => 'required']);
});

it('validates package heading is required', function () {
    Livewire::test(CreatePackage::class)
        ->set('data.name', 'Test')
        ->set('data.heading', '')
        ->set('data.category_name', 'home')
        ->call('create')
        ->assertHasFormErrors(['heading' => 'required']);
});

it('validates package category is required', function () {
    Livewire::test(CreatePackage::class)
        ->set('data.name', 'Test')
        ->set('data.heading', 'Test Heading')
        ->set('data.category_name', '')
        ->call('create')
        ->assertHasFormErrors(['category_name' => 'required']);
});

it('validates discount must be numeric', function () {
    Livewire::test(CreatePackage::class)
        ->set('data.name', 'Test')
        ->set('data.heading', 'Test')
        ->set('data.discount', 'not-a-number')
        ->set('data.category_name', 'home')
        ->call('create')
        ->assertHasFormErrors(['discount' => 'numeric']);
});

it('validates discount must be between 0 and 100', function () {
    Livewire::test(CreatePackage::class)
        ->set('data.name', 'Test')
        ->set('data.heading', 'Test')
        ->set('data.discount', 150)
        ->set('data.category_name', 'home')
        ->call('create')
        ->assertHasFormErrors(['discount' => 'max']);
});
