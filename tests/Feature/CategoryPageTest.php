<?php

use App\Models\Category;
use App\Models\Language;

beforeEach(function () {
    Language::query()->delete();
    Language::create(['name' => 'english', 'code' => 'en', 'sort_order' => 0]);
    Language::create(['name' => 'macedonian', 'code' => 'mk', 'sort_order' => 1]);

    Category::query()->delete();

    $this->category = Category::create([
        'priority' => 1,
        'name' => 'Business Test',
        'link' => 'business-test',
        'menu_status' => 1,
    ]);
});

test('renders a category page', function () {
    $this->get(route('category.index', $this->category->category_id))
        ->assertOk()
        ->assertSee('Business Test')
        ->assertSee('This is example data for category.');
});

test('shows the macedonian description after switching language', function () {
    $this->get(route('lang.change', 'macedonian'));

    $this->get(route('category.index', $this->category->category_id))
        ->assertOk()
        ->assertSee('Пример за категорија');
});

test('returns 404 for a missing category', function () {
    $this->get(route('category.index', 999999))->assertNotFound();
});
