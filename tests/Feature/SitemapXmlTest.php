<?php

use App\Models\Menu;
use Illuminate\Support\Facades\Http;

test('the sitemap xml endpoint writes a sitemap from the menu tree', function () {
    $path = public_path('sitemap.xml');
    $existedBefore = file_exists($path);

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

    $response = $this->get(route('sitemap_xml'));

    $response->assertOk();
    $response->assertSee('Wrote:');
    expect(file_exists($path))->toBeTrue();

    $contents = file_get_contents($path);
    expect($contents)->toContain(url('about-us'));
    expect($contents)->toContain(url('we-serve'));
    expect($contents)->toContain('<priority>1.00</priority>');
    expect($contents)->toContain('<priority>0.80</priority>');

    if (! $existedBefore && file_exists($path)) {
        unlink($path);
    }
});

test('the sitemap ping endpoint reports a successful ping', function () {
    Http::fake(fn () => Http::response('', 200));

    $this->get(route('sitemap_xml_upload'))
        ->assertOk()
        ->assertSee('success', false);
});

test('the sitemap ping endpoint reports a failed ping', function () {
    Http::fake(fn () => Http::response('', 500));

    $this->get(route('sitemap_xml_upload'))
        ->assertOk()
        ->assertSee('try again', false);
});
