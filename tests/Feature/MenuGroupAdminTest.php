<?php

use App\Filament\Admin\Resources\MenuGroupResource\Pages\CreateMenuGroup;
use App\Filament\Admin\Resources\MenuGroupResource\Pages\EditMenuGroup;
use App\Filament\Admin\Resources\MenuGroupResource\Pages\ListMenuGroups;
use App\Models\Admin;
use App\Models\Menu;
use App\Models\MenuGroup;
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
});

test('menu groups page renders and lists groups', function () {
    MenuGroup::create(['title' => 'Main Menu']);

    $this->get('/admin/menu-groups')
        ->assertSuccessful()
        ->assertSee('Main Menu');
});

test('admin can create a menu group', function () {
    Livewire::test(CreateMenuGroup::class)
        ->fillForm(['title' => 'Footer Menu'])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('menu_group', ['title' => 'Footer Menu']);
});

test('admin can rename a menu group', function () {
    $group = MenuGroup::create(['title' => 'Old Title']);

    Livewire::test(EditMenuGroup::class, ['record' => $group->id])
        ->fillForm(['title' => 'New Title'])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('menu_group', ['id' => $group->id, 'title' => 'New Title']);
});

test('deleting a menu group also deletes its menus', function () {
    $group = MenuGroup::create(['title' => 'Temp Group']);

    Menu::create([
        'group_id' => $group->id,
        'title' => 'Menu Item',
        'url' => '/about',
        'menu_type' => '',
    ]);

    Livewire::test(ListMenuGroups::class)
        ->callTableAction('delete', $group);

    $this->assertDatabaseMissing('menu_group', ['id' => $group->id]);
    $this->assertDatabaseMissing('menu', ['group_id' => $group->id]);
});

test('menu group 1 cannot be deleted', function () {
    MenuGroup::insert(['id' => 1, 'title' => 'Main']);

    Livewire::test(ListMenuGroups::class)
        ->assertTableActionHidden('delete', 1);

    $this->assertDatabaseHas('menu_group', ['id' => 1]);
});
