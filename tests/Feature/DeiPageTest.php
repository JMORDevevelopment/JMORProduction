<?php

use Illuminate\Support\Facades\DB;

const DEI_LINK = 'our-dei-diversiety-equity-inclusion-non-discrimination-policy';

beforeEach(function () {
    DB::table('menu')->insert([
        'id' => 2,
        'parent_id' => 0,
        'title' => 'About us',
        'url' => 'about-us',
        'position' => 2,
        'group_id' => 1,
        'menu_type' => 'Pages',
        'page_id' => 0,
    ]);

    $migration = include database_path('migrations/2026_11_13_140921_restore_dei_page_and_navigation.php');
    $migration->up();
});

test('the dedicated dei page renders its policy and approved image', function () {
    $this->get(route(DEI_LINK))
        ->assertSuccessful()
        ->assertSee('Our DEI (Diversity, Equity & Inclusion) Non-Discrimination Policy')
        ->assertSee('Introduction: Commitment to Diversity and Inclusion')
        ->assertSee('Effective Date')
        ->assertSee('src="'.asset('assets/images/pages/jmor-dei.jpg').'"', false)
        ->assertDontSee('Coming Soon');
});

test('the dei page is linked from the about navigation', function () {
    $this->get(route(DEI_LINK))
        ->assertSuccessful()
        ->assertSee('Our DEI (Non-Discrimination Policy)')
        ->assertSee('href="'.url(DEI_LINK).'"', false)
        ->assertSee('Our DEI Policy');
});

test('the dei page and menu entry are restored once only', function () {
    $migration = include database_path('migrations/2026_11_13_140921_restore_dei_page_and_navigation.php');
    $migration->up();

    expect(DB::table('pages')->where('link', DEI_LINK)->count())->toBe(1)
        ->and(DB::table('menu')->where('url', DEI_LINK)->count())->toBe(1);
});

test('the migration preserves cms edits to dei content', function () {
    DB::table('pages')->where('link', DEI_LINK)->update([
        'description' => '<p>Updated DEI copy</p>',
        'image' => 'uploads/pages/updated-dei.jpg',
    ]);

    $migration = include database_path('migrations/2026_11_13_140921_restore_dei_page_and_navigation.php');
    $migration->up();

    $page = DB::table('pages')->where('link', DEI_LINK)->first();

    expect($page->description)->toBe('<p>Updated DEI copy</p>')
        ->and($page->image)->toBe('uploads/pages/updated-dei.jpg');
});
