<?php

use App\Models\Page;

beforeEach(function () {
    Page::create([
        'name' => 'About Us',
        'link' => 'about-us',
        'description' => '<p>JMOR is a leading IT services company.</p>',
        'image' => '',
        'meta_title' => 'About JMOR',
        'meta_description' => 'Learn about JMOR',
        'meta_keywords' => 'jmor, about',
    ]);

    Page::create([
        'name' => 'Our Mission',
        'link' => 'our-mission',
        'description' => '<p>Our mission is to provide exceptional IT services.</p>',
        'image' => 'uploads/pages/mission.jpg',
        'meta_title' => '',
        'meta_description' => '',
        'meta_keywords' => '',
    ]);

    Page::create([
        'name' => 'Privacy Policy',
        'link' => 'privacy-policy',
        'description' => '<p>Privacy policy content goes here.</p>',
        'image' => '',
        'meta_title' => 'Privacy Policy',
        'meta_description' => 'JMOR privacy policy',
        'meta_keywords' => 'privacy',
    ]);
});

test('a cms page renders its content by link', function () {
    $this->get(route('about-us'))
        ->assertSuccessful()
        ->assertSee('About Us')
        ->assertSee('JMOR is a leading IT services company.');
});

test('a cms page with an image shows the image', function () {
    $this->get(route('our-mission'))
        ->assertSuccessful()
        ->assertSee('Our mission is to provide exceptional IT services.')
        ->assertSee('uploads/pages/mission.jpg');
});

test('a cms page without an image does not render a page header image', function () {
    $this->get(route('about-us'))
        ->assertSuccessful()
        ->assertDontSee('uploads/pages/');
});

test('a cms page uses meta_title when available', function () {
    $this->get(route('about-us'))
        ->assertSuccessful()
        ->assertSee('About JMOR');
});

test('a cms page falls back to name when meta_title is empty', function () {
    $this->get(route('our-mission'))
        ->assertSuccessful()
        ->assertSee('Our Mission');
});

test('unknown page links return a 404', function () {
    $this->get(route('privacy-policy'))
        ->assertSuccessful();

    Page::where('link', 'privacy-policy')->delete();

    $this->get(route('privacy-policy'))->assertNotFound();
});

test('multiple cms pages are independently accessible', function () {
    $this->get(route('about-us'))->assertSuccessful()->assertSee('About Us');
    $this->get(route('our-mission'))->assertSuccessful()->assertSee('Our Mission');
    $this->get(route('privacy-policy'))->assertSuccessful()->assertSee('Privacy Policy');
});
