<?php

use App\Models\Page;
use Illuminate\Support\Facades\DB;

const RESTORED_PAGES_MIGRATION = '2026_11_13_150000_restore_coming_soon_content_pages.php';

function runRestoredPagesMigration(): void
{
    $migration = include database_path('migrations/'.RESTORED_PAGES_MIGRATION);
    $migration->up();
}

dataset('restored content pages', [
    'it service providers' => [
        'it-service-providers-in-new-jersey-for-homes-and-businesses',
        'IT Service Providers in New Jersey for Homes and Businesses',
        'Serving homeowners and businesses across Bergen, Morris, and Passaic Counties',
        'assets/images/pages/it-service-providers.jpg',
    ],
    'ai solutions' => [
        'ai-solutions-for-businesses-consumers-safe-secure-ai-integrations-in-nj-and-beyond',
        'AI Solutions for Businesses & Consumers',
        'JMOR: Pioneering AI Solutions with Safety, Security, and Human Oversight',
        'assets/images/pages/ai-solutions.jpg',
    ],
    'it support solutions' => [
        'it-support-solutions-for-new-jersey-businesses-and-homes-the-jmor-connection',
        'IT Support Solutions for New Jersey Businesses and Homes',
        'Comprehensive IT Support Solutions for Small Businesses and Homes in New Jersey',
        null,
    ],
    'remote employee engagement' => [
        'remote-employee-engagement-solutions',
        'Remote Employee Engagement Solutions',
        'Some of the Solutions we have helped clients with are',
        'assets/images/pages/remote-employee-engagement.jpg',
    ],
    'technology guides' => [
        'technology-guides-it-resources-the-jmor-connection-inc',
        'Technology Guides & IT Resources',
        'Practical IT Resources for Homes',
        'assets/images/pages/technology-guides.jpg',
    ],
    'tech byte-sized blunders' => [
        'jmor-tech-byte-sized-blunders-laugh-and-learn',
        'JMOR Tech Byte-Sized Blunders',
        'Where Laughter Meets Technology',
        'assets/images/pages/tech-byte-sized-blunders.jpg',
    ],
]);

test('the restored page renders its real content instead of the placeholder', function (string $link, string $name, string $body, ?string $image) {
    runRestoredPagesMigration();

    $response = $this->get('/'.$link)
        ->assertSuccessful()
        ->assertSee($name)
        ->assertSee($body)
        ->assertDontSee('Coming Soon');

    if ($image !== null) {
        $response->assertSee('src="'.asset($image).'"', false);
    }
})->with('restored content pages');

test('long restored page copy is rendered to the end', function () {
    runRestoredPagesMigration();

    $this->get('/it-service-providers-in-new-jersey-for-homes-and-businesses')
        ->assertSuccessful()
        ->assertSee('improve your technology for consistent performance');
});

test('the restored page images are part of the deployed assets', function () {
    foreach ([
        'assets/images/pages/it-service-providers.jpg',
        'assets/images/pages/ai-solutions.jpg',
        'assets/images/pages/remote-employee-engagement.jpg',
        'assets/images/pages/technology-guides.jpg',
        'assets/images/pages/tech-byte-sized-blunders.jpg',
    ] as $image) {
        expect(public_path($image))->toBeFile();
    }
});

test('restored pages keep their natural contextual internal links', function (string $link, array $expectedLinks) {
    runRestoredPagesMigration();

    $response = $this->get('/'.$link)->assertSuccessful();

    foreach ($expectedLinks as $href) {
        $response->assertSee('href="'.$href, false);
    }
})->with([
    'it service providers' => [
        'it-service-providers-in-new-jersey-for-homes-and-businesses',
        ['/', '/blog'],
    ],
    'it support solutions' => [
        'it-support-solutions-for-new-jersey-businesses-and-homes-the-jmor-connection',
        ['/blog'],
    ],
    'technology guides' => [
        'technology-guides-it-resources-the-jmor-connection-inc',
        ['/blog', '/network-cyber-security-internet-safety', '/our-mission', '/why-choose-jmor'],
    ],
    'tech byte-sized blunders' => [
        'jmor-tech-byte-sized-blunders-laugh-and-learn',
        ['/why-choose-jmor', 'https://www.youtube.com/playlist'],
    ],
]);

test('the internal contextual links point at working pages', function () {
    runRestoredPagesMigration();

    foreach (['network-cyber-security-internet-safety', 'our-mission', 'why-choose-jmor'] as $link) {
        Page::create([
            'name' => $link,
            'link' => $link,
            'priority' => 0,
            'slider_status' => 0,
            'menu_location' => 0,
            'description' => '<p>Linked page</p>',
            'image' => '',
            'form_id' => 0,
            'menu_status' => 0,
            'meta_title' => '',
            'meta_keywords' => '',
            'meta_description' => '',
        ]);
    }

    foreach (['/blog', '/network-cyber-security-internet-safety', '/our-mission', '/why-choose-jmor', '/'] as $path) {
        $this->get($path)->assertSuccessful();
    }
});

test('the print management legacy url permanently redirects to the home page', function () {
    $this->get('/print-management-solution')
        ->assertStatus(301)
        ->assertRedirect('/');
});

test('the linktree page permanently redirects to the jmor linktree profile', function () {
    $this->get('/linktree')
        ->assertStatus(301)
        ->assertRedirect('https://linktr.ee/johncmorleyserialentrepreneur');
});

test('the restored pages migration is idempotent', function () {
    runRestoredPagesMigration();
    runRestoredPagesMigration();

    foreach ([
        'it-service-providers-in-new-jersey-for-homes-and-businesses',
        'ai-solutions-for-businesses-consumers-safe-secure-ai-integrations-in-nj-and-beyond',
        'it-support-solutions-for-new-jersey-businesses-and-homes-the-jmor-connection',
        'remote-employee-engagement-solutions',
        'technology-guides-it-resources-the-jmor-connection-inc',
        'jmor-tech-byte-sized-blunders-laugh-and-learn',
    ] as $link) {
        expect(DB::table('pages')->where('link', $link)->count())->toBe(1);
    }
});

test('the migration preserves cms edits to restored page content', function () {
    runRestoredPagesMigration();

    DB::table('pages')
        ->where('link', 'remote-employee-engagement-solutions')
        ->update(['description' => '<p>Updated by the editor</p>']);

    runRestoredPagesMigration();

    expect(DB::table('pages')->where('link', 'remote-employee-engagement-solutions')->value('description'))
        ->toBe('<p>Updated by the editor</p>');
});
