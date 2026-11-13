<?php

use App\Models\Page;

beforeEach(function () {
    foreach (['about-us', 'our-mission', 'why-choose-jmor'] as $link) {
        Page::create([
            'name' => $link,
            'link' => $link,
            'priority' => 0,
            'slider_status' => 0,
            'menu_location' => 0,
            'description' => '<p>Page copy</p>',
            'image' => '',
            'form_id' => 0,
            'menu_status' => 0,
            'meta_title' => '',
            'meta_keywords' => '',
            'meta_description' => '',
        ]);
    }
});

test('the homepage uses real calls to action instead of unsupported graphic placeholders', function () {
    $this->get(route('home'))
        ->assertSuccessful()
        ->assertDontSee('Partner badge')
        ->assertDontSee('Certification</div>', false)
        ->assertDontSee('Client logo')
        ->assertDontSee('>Post<', false)
        ->assertDontSee('jm-partners__grid', false)
        ->assertDontSee('jm-news__insta-grid', false)
        ->assertSee('Ask about our credentials')
        ->assertSee('View Instagram profile')
        ->assertSee('href="https://www.instagram.com/gosocialjmor/"', false);
});

test('no page shows placeholder trust badges or unverified partner logos', function (string $route) {
    $this->get(route($route))
        ->assertSuccessful()
        ->assertDontSee('Partner badge')
        ->assertDontSee('Client logo')
        ->assertDontSee('Certification</div>', false)
        ->assertDontSee('jm-partners__grid', false)
        ->assertDontSee('assets_2/img/clients', false);
})->with([
    'home',
    'about-us',
    'our-mission',
    'why-choose-jmor',
    'testimonials',
    'contact',
    'packages',
]);

test('the template client logos are no longer shipped', function () {
    expect(public_path('assets_2/img/clients'))->not->toBeDirectory();
});
