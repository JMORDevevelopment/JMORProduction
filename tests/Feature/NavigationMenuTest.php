<?php

use App\Models\Menu;
use App\Models\Page;

function seedMenuTree(): void
{
    $parent = Menu::create([
        'parent_id' => 0,
        'title' => 'About Us',
        'url' => 'about-us',
        'position' => 1,
        'group_id' => 1,
        'menu_type' => 'Pages',
        'page_id' => 0,
    ]);

    Menu::create([
        'parent_id' => $parent->id,
        'title' => 'We Serve',
        'url' => 'we-serve',
        'position' => 1,
        'group_id' => 1,
        'menu_type' => 'Pages',
        'page_id' => 0,
    ]);

    $media = Menu::create([
        'parent_id' => $parent->id,
        'title' => 'Media',
        'url' => 'media',
        'position' => 2,
        'group_id' => 1,
        'menu_type' => 'Pages',
        'page_id' => 0,
    ]);

    Menu::create([
        'parent_id' => $media->id,
        'title' => 'Testimonials',
        'url' => 'testimonials',
        'position' => 1,
        'group_id' => 1,
        'menu_type' => 'Pages',
        'page_id' => 0,
    ]);
}

function seedContentPages(): void
{
    Page::create([
        'link' => 'about-us',
        'name' => 'About Us',
        'priority' => 1,
        'slider_status' => 0,
        'menu_location' => 0,
        'description' => '<p>About page</p>',
        'image' => '',
        'meta_title' => 'About Us',
        'meta_keywords' => '',
        'meta_description' => '',
        'menu_status' => 0,
    ]);

    Page::create([
        'link' => 'we-serve',
        'name' => 'We Serve',
        'priority' => 2,
        'slider_status' => 0,
        'menu_location' => 0,
        'description' => '<p>We Serve page</p>',
        'image' => '',
        'meta_title' => 'We Serve',
        'meta_keywords' => '',
        'meta_description' => '',
        'menu_status' => 0,
    ]);
}

beforeEach(function () {
    seedMenuTree();
    seedContentPages();
});

test('a menu url matching the current route name does not crash the page', function () {
    $this->get(route('testimonials'))->assertSuccessful();
});

test('a top level menu url matching the current route name does not crash the page', function () {
    $this->get(route('about-us'))
        ->assertSuccessful()
        ->assertSee(route('about-us'), false);
});

test('a child menu url matching the current route name does not crash the page', function () {
    $this->get(route('we-serve'))->assertSuccessful();
});

test('menu urls that do not match the current route fall back to path urls', function () {
    $this->get(route('testimonials'))
        ->assertSuccessful()
        ->assertSee(url('about-us'), false);
});
