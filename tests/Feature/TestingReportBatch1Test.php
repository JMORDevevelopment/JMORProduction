<?php

use Illuminate\Support\Facades\DB;

test('the sitewide phone number is 973 394 1000', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('(973) 394-1000')
        ->assertSee('tel:+19733941000', false)
        ->assertDontSee('(877) 767-5667');
});

test('the header claims serving new jersey since 1993', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('since 1993')
        ->assertDontSee('since 1997');
});

test('tenure claims use the dynamic founding year', function () {
    $years = now()->year - 1993;

    $this->get(route('home'))
        ->assertOk()
        ->assertSee($years.'+ years')
        ->assertSee('Having over '.$years.' years')
        ->assertDontSee('28+ years')
        ->assertDontSee('over 28 years');
});

test('the pre footer call to action reads correctly', function () {
    $this->get(route('sitemap'))
        ->assertOk()
        ->assertSee('We listen to our clients')
        ->assertDontSee('We to listen');
});

test('the coming soon page shows the full corporate name in the browser title', function () {
    $this->get(route('it-service-providers-in-new-jersey-for-homes-and-businesses'))
        ->assertOk()
        ->assertSee('<title>Coming Soon - The JMOR Connection, Inc.</title>', false);
});

it('fixes spelling errors in the published service plan bullets', function () {
    DB::table('home_tab')->insert([
        'tab_title' => 'Spelling Fix Fixture',
        'tab_description' => null,
        'tab_list' => json_encode(['Hardware inspection and maintenence', 'System disgnostics']),
        'benefits' => 'x',
        'cost' => 'x',
    ]);

    $migration = include database_path('migrations/2026_11_09_121345_fix_service_plan_spelling_errors.php');
    $migration->up();

    $row = DB::table('home_tab')->where('tab_title', 'Spelling Fix Fixture')->first();

    expect($row->tab_list)->toContain('maintenance')->not->toContain('maintenence');
    expect($row->tab_list)->toContain('diagnostics')->not->toContain('disgnostics');
});

it('corrects the our mission page tenure claim', function () {
    DB::table('pages')->insert([
        'link' => 'our-mission-test',
        'name' => 'Our Mission Test',
        'priority' => 1,
        'menu_location' => 0,
        'description' => '<p>Having over 28 years of experience</p>',
        'image' => '',
        'meta_title' => '',
        'meta_keywords' => '',
        'meta_description' => '',
    ]);

    $migration = include database_path('migrations/2026_11_09_121346_fix_mission_page_tenure_claim.php');
    $migration->up();

    $row = DB::table('pages')->where('link', 'our-mission-test')->first();

    expect($row->description)->toContain('Having over 33 years')->not->toContain('28 years');
});
