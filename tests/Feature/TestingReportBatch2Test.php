<?php

use App\Support\RichText;
use Illuminate\Support\Facades\DB;

it('keeps internal relative links in sanitized content', function () {
    $html = (string) RichText::sanitize(
        '<p><a href="/service/it-tech-support">backup</a> and <a href="javascript:alert(1)">bad</a></p><script>x</script>'
    );

    expect($html)->toContain('href="/service/it-tech-support"')
        ->not->toContain('javascript:')
        ->not->toContain('<script');
});

test('content links are visually distinct in page content', function () {
    $css = file_get_contents(public_path('assets_2/css/theme.css'));

    expect(preg_match('/\.blog-post a \{[^}]+\}/', $css, $m) ?: 0)->toBe(1);
    expect($m[0])->toContain('text-decoration: underline')->toContain('#014073');
});

test('page content links render as clickable links', function () {
    DB::table('pages')->insert([
        'link' => 'it-tech-support-services-in-nj',
        'name' => 'IT Tech Support',
        'priority' => 1,
        'menu_location' => 0,
        'description' => '<p>From routine PC maintenance, <a href="/service/it-tech-support">backup</a> and other services.</p>',
        'image' => '',
        'meta_title' => '',
        'meta_keywords' => '',
        'meta_description' => '',
    ]);

    $this->get(route('it-tech-support-services-in-nj'))
        ->assertOk()
        ->assertSee('href="/service/it-tech-support"', false)
        ->assertSee('>backup</a>', false);
});

test('the homepage why choose cta points to the why choose page', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('href="'.route('why-choose-jmor').'" class="jm-btn jm-btn--orange"', false)
        ->assertDontSee('href="'.route('about').'"', false);
});

test('the homepage has separate mission and dei cards', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee(route('our-mission'), false)
        ->assertSee('Our Mission')
        ->assertSee(route('our-dei-diversiety-equity-inclusion-non-discrimination-policy'), false)
        ->assertSee('DEI Policy')
        ->assertDontSee('Our mission &amp; DEI', false);
});

it('rewrites specialist service links to beta paths', function () {
    DB::table('slider')->insert([
        'slider_name' => 'Prod Link Fixture',
        'slider_link' => 'https://jmor.com/it-tech-support-services-in-nj',
    ]);
    DB::table('slider')->insert([
        'slider_name' => 'Legacy Link Fixture',
        'slider_link' => 'https://jmor.com/network-cyber-security-internet',
    ]);

    $migration = include database_path('migrations/2026_11_09_142156_fix_slider_links_to_beta_paths.php');
    $migration->up();

    expect(DB::table('slider')->where('slider_name', 'Prod Link Fixture')->value('slider_link'))
        ->toBe('/it-tech-support-services-in-nj');
    expect(DB::table('slider')->where('slider_name', 'Legacy Link Fixture')->value('slider_link'))
        ->toBe('/network-cyber-security-internet-safety');
});

it('restores contextual internal links to beta destinations', function () {
    DB::table('pages')->insert([
        'link' => 'it-tech-support-services-in-nj',
        'name' => 'IT Tech',
        'priority' => 1,
        'menu_location' => 0,
        'description' => '<p>From routine PC maintenance to on-site replacement, <a href="https://www.jmor.com/solutions/nj-it-tech-support-services/backup/">backup</a> and other services.</p>',
        'image' => '',
        'meta_title' => '',
        'meta_keywords' => '',
        'meta_description' => '',
    ]);
    DB::table('press_releases')->insert([
        'link' => 'press-fixture',
        'name' => 'Press Fixture',
        'description' => '<p><a href="https://jmor.com/">Tech</a> businesses have a new method.</p>',
        'image' => '',
        'meta_title' => '',
        'meta_keywords' => '',
        'meta_description' => '',
    ]);

    $migration = include database_path('migrations/2026_11_09_142157_restore_contextual_internal_links.php');
    $migration->up();

    $page = DB::table('pages')->where('link', 'it-tech-support-services-in-nj')->value('description');
    expect($page)->toContain('href="/service/it-tech-support"')->not->toContain('jmor.com/solutions');

    $press = DB::table('press_releases')->where('link', 'press-fixture')->value('description');
    expect($press)->toContain('href="/"')->not->toContain('https://jmor.com/');
});

it('replaces the old phone number in settings and page content', function () {
    DB::table('settings')->insert(['option' => 'phone', 'value' => '(877) 767-5667a']);
    DB::table('pages')->insert([
        'link' => 'phone-content-fixture',
        'name' => 'Phone Fixture',
        'priority' => 1,
        'menu_location' => 0,
        'description' => '<p>Call us now!!</p><p>(877) 767-5667</p><p>Telephone number:</p><p>(877)767-5667</p>',
        'image' => '',
        'meta_title' => '',
        'meta_keywords' => '',
        'meta_description' => '',
    ]);

    $migration = include database_path('migrations/2026_11_09_142159_fix_old_phone_in_settings_and_content.php');
    $migration->up();

    expect(DB::table('settings')->where('option', 'phone')->value('value'))->toBe('(973) 394-1000');

    $content = DB::table('pages')->where('link', 'phone-content-fixture')->value('description');
    expect($content)->toContain('(973) 394-1000')->not->toContain('767-5667');
});

it('adds contextual internal links on substantive pages', function () {
    DB::table('pages')->insert([
        'link' => 'why-choose-jmor',
        'name' => 'Why Choose',
        'priority' => 1,
        'menu_location' => 0,
        'description' => '<p>fixing your computers and networking systems also play a key role</p><p>give you the best solutions available.</p>',
        'image' => '',
        'meta_title' => '',
        'meta_keywords' => '',
        'meta_description' => '',
    ]);
    DB::table('pages')->insert([
        'link' => 'it-tech-support-services-in-nj',
        'name' => 'IT Tech',
        'priority' => 2,
        'menu_location' => 0,
        'description' => '<p>We give you managed network support services that enable proactive monitoring.</p>',
        'image' => '',
        'meta_title' => '',
        'meta_keywords' => '',
        'meta_description' => '',
    ]);

    $migration = include database_path('migrations/2026_11_09_142200_add_contextual_internal_links.php');
    $migration->up();

    $whyChoose = DB::table('pages')->where('link', 'why-choose-jmor')->value('description');
    expect($whyChoose)
        ->toContain('<a href="/it-tech-support-services-in-nj">computers and networking systems</a>')
        ->toContain('<a href="/custom-built-solutions">best solutions available</a>');

    $itTech = DB::table('pages')->where('link', 'it-tech-support-services-in-nj')->value('description');
    expect($itTech)
        ->toContain('<a href="/network-cyber-security-internet-safety">managed network support services</a>');
});
