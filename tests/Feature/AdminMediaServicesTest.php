<?php

use App\Filament\Admin\Resources\CategoryRadioShowResource\Pages\CreateCategoryRadioShow;
use App\Filament\Admin\Resources\HomeTabResource\Pages\CreateHomeTab;
use App\Filament\Admin\Resources\HomeTabResource\Pages\EditHomeTab;
use App\Filament\Admin\Resources\MediaVideoResource\Pages\CreateMediaVideo;
use App\Filament\Admin\Resources\RadioShowResource\Pages\CreateRadioShow;
use App\Filament\Admin\Resources\ServiceResource\Pages\CreateService;
use App\Filament\Admin\Resources\SliderResource\Pages\CreateSlider;
use App\Filament\Admin\Resources\SliderResource\Pages\EditSlider;
use App\Filament\Admin\Resources\SliderResource\Pages\ListSliders;
use App\Models\Admin;
use App\Models\CategoryRadioShow;
use App\Models\HomeTab;
use App\Models\Service;
use App\Models\Slider;
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
});

// ─── Slider List ────────────────────────────────────────────────────────

test('admin can access slider list page', function () {
    $this->get('/admin/sliders')->assertSuccessful();
});

test('slider list page shows existing entries', function () {
    Slider::create([
        'slider_name' => 'Existing Slider',
        'slider_desc' => '<p>Description</p>',
        'slider_image' => 'uploads/slider/test.jpg',
        'slider_link' => 'https://example.com',
        'priority' => 1,
    ]);

    Livewire::test(ListSliders::class)
        ->assertCanRenderTableColumn('slider_name');
});

// ─── Slider Create ──────────────────────────────────────────────────────

test('admin can access slider create page', function () {
    $this->get('/admin/sliders/create')->assertSuccessful();
});

test('admin can create a slider via livewire', function () {
    Livewire::test(CreateSlider::class)
        ->set('data.slider_name', 'Test Slider')
        ->set('data.slider_desc', '<p>Test description</p>')
        ->set('data.slider_link', 'https://example.com/page')
        ->set('data.priority', 1)
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('slider', [
        'slider_name' => 'Test Slider',
        'slider_link' => 'https://example.com/page',
    ]);
});

// ─── Slider Edit ────────────────────────────────────────────────────────

test('admin can access slider edit page', function () {
    $slider = Slider::create([
        'slider_name' => 'Existing Slider',
        'slider_desc' => '<p>Description</p>',
        'slider_image' => 'uploads/slider/test.jpg',
        'slider_link' => 'https://example.com',
        'priority' => 1,
    ]);

    $this->get("/admin/sliders/{$slider->slider_id}/edit")->assertSuccessful();
});

test('admin can update a slider via livewire', function () {
    $slider = Slider::create([
        'slider_name' => 'Old Title',
        'slider_desc' => '<p>Old</p>',
        'slider_image' => 'uploads/slider/test.jpg',
        'slider_link' => 'https://example.com',
        'priority' => 1,
    ]);

    Livewire::test(EditSlider::class, ['record' => $slider->slider_id])
        ->set('data.slider_name', 'Updated Title')
        ->set('data.slider_desc', '<p>Updated</p>')
        ->set('data.slider_link', 'https://example.com/updated')
        ->set('data.priority', 2)
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('slider', [
        'slider_id' => $slider->slider_id,
        'slider_name' => 'Updated Title',
    ]);
});

// ─── Slider Delete ──────────────────────────────────────────────────────

test('admin can delete a slider', function () {
    $slider = Slider::create([
        'slider_name' => 'To Delete',
        'slider_desc' => '<p>Content</p>',
        'slider_image' => 'uploads/slider/test.jpg',
        'slider_link' => 'https://example.com',
        'priority' => 1,
    ]);

    Livewire::test(ListSliders::class)
        ->callTableAction('delete', $slider);

    $this->assertDatabaseMissing('slider', ['slider_id' => $slider->slider_id]);
});

// ─── Service ────────────────────────────────────────────────────────────

test('admin can access service list page', function () {
    $this->get('/admin/services')->assertSuccessful();
});

test('admin can create a service via livewire', function () {
    Livewire::test(CreateService::class)
        ->set('data.title', 'Test Service')
        ->set('data.description', '<p>Service content</p>')
        ->set('data.meta_title', 'Test Meta')
        ->set('data.keywords', 'test, service')
        ->set('data.meta_description', 'Test description')
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('service', [
        'title' => 'Test Service',
        'link' => 'test-service',
    ]);
});

// ─── Media Video ────────────────────────────────────────────────────────

test('admin can access media video list page', function () {
    $this->get('/admin/media-videos')->assertSuccessful();
});

test('admin can create a media video via livewire', function () {
    Livewire::test(CreateMediaVideo::class)
        ->set('data.name', 'Test Video')
        ->set('data.description', '<p>Video content</p>')
        ->set('data.video_link', 'https://youtube.com/watch?v=test')
        ->set('data.published', '2026-01-01 00:00:00')
        ->set('data.meta_title', 'Test Meta')
        ->set('data.meta_keywords', 'test, video')
        ->set('data.meta_description', 'Test description')
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('media_video', [
        'name' => 'Test Video',
        'video_link' => 'https://youtube.com/watch?v=test',
    ]);
});

// ─── Category Radio Show ────────────────────────────────────────────────

test('admin can access category radio show list page', function () {
    $this->get('/admin/category-radio-shows')->assertSuccessful();
});

test('admin can create a category radio show via livewire', function () {
    Livewire::test(CreateCategoryRadioShow::class)
        ->set('data.title', 'Test Category')
        ->set('data.sub_title', 'Subtitle')
        ->set('data.description', '<p>Category content</p>')
        ->set('data.link', 'test-category')
        ->set('data.menu_status', true)
        ->set('data.published', '2026-01-01 00:00:00')
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('category_radio_show', [
        'title' => 'Test Category',
        'link' => 'test-category',
    ]);
});

test('admin can create a child category', function () {
    $parent = CategoryRadioShow::create([
        'title' => 'Parent Category',
        'link' => 'parent-category',
        'menu_status' => 1,
        'parent_id' => 0,
        'sub_title' => 'Parent subtitle',
        'description' => '<p>Parent</p>',
        'image' => 'uploads/category_radio_show/test.jpg',
        'published' => '2026-01-01 00:00:00',
    ]);

    Livewire::test(CreateCategoryRadioShow::class)
        ->set('data.title', 'Child Category')
        ->set('data.sub_title', 'Child subtitle')
        ->set('data.description', '<p>Child</p>')
        ->set('data.link', 'child-category')
        ->set('data.parent_id', $parent->id)
        ->set('data.menu_status', true)
        ->set('data.published', '2026-01-01 00:00:00')
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('category_radio_show', [
        'title' => 'Child Category',
        'parent_id' => $parent->id,
    ]);
});

// ─── Radio Show ─────────────────────────────────────────────────────────

test('admin can access radio show list page', function () {
    $this->get('/admin/radio-shows')->assertSuccessful();
});

test('admin can create a radio show via livewire', function () {
    $category = CategoryRadioShow::create([
        'title' => 'Test Category',
        'link' => 'test-category',
        'menu_status' => 1,
        'parent_id' => 0,
        'sub_title' => 'Subtitle',
        'description' => '<p>Content</p>',
        'image' => 'uploads/category_radio_show/test.jpg',
        'published' => '2026-01-01 00:00:00',
    ]);

    Livewire::test(CreateRadioShow::class)
        ->set('data.name', 'Test Radio Show')
        ->set('data.description', '<p>Show content</p>')
        ->set('data.category_id', $category->id)
        ->set('data.show_date', '2026-06-15')
        ->set('data.published', '2026-01-01 00:00:00')
        ->set('data.meta_title', 'Test Meta')
        ->set('data.meta_keywords', 'test, radio')
        ->set('data.meta_description', 'Test description')
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('radio_show', [
        'name' => 'Test Radio Show',
        'category_id' => $category->id,
    ]);
});

// ─── Home Tab ───────────────────────────────────────────────────────────

test('admin can access home tab list page', function () {
    $this->get('/admin/home-tabs')->assertSuccessful();
});

test('admin can create a home tab via livewire', function () {
    Livewire::test(CreateHomeTab::class)
        ->set('data.tab_title', 'Basic Plan')
        ->set('data.tab_description', 'Our basic plan features')
        ->set('data.tab_list', [['item' => 'Feature 1'], ['item' => 'Feature 2']])
        ->set('data.benefits', [['item' => 'Benefit 1']])
        ->set('data.cost', [['item' => '$99/month']])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('home_tab', [
        'tab_title' => 'Basic Plan',
    ]);

    $tab = HomeTab::where('tab_title', 'Basic Plan')->first();
    $this->assertEquals(['Feature 1', 'Feature 2'], $tab->tab_list);
    $this->assertEquals(['Benefit 1'], $tab->benefits);
    $this->assertEquals(['$99/month'], $tab->cost);
});

test('admin can edit a home tab with repeater conversion', function () {
    $tab = HomeTab::create([
        'tab_title' => 'Old Plan',
        'tab_description' => 'Old description',
        'tab_list' => ['Old Feature 1', 'Old Feature 2'],
        'benefits' => ['Old Benefit'],
        'cost' => ['$99'],
    ]);

    Livewire::test(EditHomeTab::class, ['record' => $tab->tab_id])
        ->set('data.tab_title', 'Updated Plan')
        ->set('data.tab_list', [['item' => 'New Feature 1'], ['item' => 'New Feature 2'], ['item' => 'New Feature 3']])
        ->set('data.benefits', [['item' => 'New Benefit']])
        ->set('data.cost', [['item' => '$199/month']])
        ->call('save')
        ->assertHasNoFormErrors();

    $tab->refresh();
    $this->assertEquals('Updated Plan', $tab->tab_title);
    $this->assertEquals(['New Feature 1', 'New Feature 2', 'New Feature 3'], $tab->tab_list);
    $this->assertEquals(['New Benefit'], $tab->benefits);
    $this->assertEquals(['$199/month'], $tab->cost);
});

// ─── Unauthenticated ───────────────────────────────────────────────────

test('unauthenticated user cannot access admin sliders', function () {
    auth()->guard('admin')->logout();

    $this->get('/admin/sliders')->assertRedirect();
});
