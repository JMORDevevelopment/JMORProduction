<?php

use App\Models\Page;

function graphicsPageFixture(array $attributes = []): Page
{
    return Page::create(array_merge([
        'name' => 'Test page',
        'link' => 'test-page',
        'priority' => 1,
        'slider_status' => 0,
        'menu_location' => 1,
        'description' => '<p>Page body copy</p>',
        'image' => '',
        'form_id' => 0,
        'menu_status' => 0,
        'meta_title' => '',
        'meta_keywords' => '',
        'meta_description' => '',
    ], $attributes));
}

test('the about us page renders its approved image above the page copy', function () {
    graphicsPageFixture([
        'name' => 'About us',
        'link' => 'about-us',
        'description' => '<p>About us body copy</p>',
        'image' => 'assets/images/pages/jmor-mission.jpg',
    ]);

    $this->get(route('about-us'))
        ->assertSuccessful()
        ->assertSee('class="page-feature-image"', false)
        ->assertSee('src="'.asset('assets/images/pages/jmor-mission.jpg').'"', false)
        ->assertSee('alt="About us"', false)
        ->assertSeeInOrder([
            asset('assets/images/pages/jmor-mission.jpg'),
            'About us body copy',
        ]);
});

test('the approved page image is part of the deployed assets', function () {
    expect(public_path('assets/images/pages/jmor-mission.jpg'))->toBeFile();
});

test('the migration restores approved images on the about and mission pages', function () {
    graphicsPageFixture(['name' => 'About us', 'link' => 'about-us']);
    graphicsPageFixture(['name' => 'Our Mission', 'link' => 'our-mission']);

    $migration = include database_path('migrations/2026_11_13_134541_restore_approved_page_images.php');
    $migration->up();

    expect(Page::where('link', 'about-us')->value('image'))
        ->toBe('assets/images/pages/jmor-mission.jpg')
        ->and(Page::where('link', 'our-mission')->value('image'))
        ->toBe('assets/images/pages/jmor-mission.jpg');
});

test('the migration does not replace an image chosen in the cms', function () {
    graphicsPageFixture([
        'name' => 'About us',
        'link' => 'about-us',
        'image' => 'uploads/pages/editor-choice.jpg',
    ]);

    $migration = include database_path('migrations/2026_11_13_134541_restore_approved_page_images.php');
    $migration->up();

    expect(Page::where('link', 'about-us')->value('image'))
        ->toBe('uploads/pages/editor-choice.jpg');
});

test('every legacy cms page image moves to a tracked asset', function () {
    $moves = [
        ['uploads/pages/1441422.jpg', 'assets/images/pages/case-study.jpg'],
        ['uploads/pages/1065250716technical-relocation-service.jpg', 'assets/images/pages/technical-relocation-service.jpg'],
        ['uploads/pages/1715462809nj-custom-built-solutions.jpg', 'assets/images/pages/custom-built-solutions.jpg'],
        ['uploads/pages/1401821031Man in Warehouse with Mask.jpg', 'assets/images/pages/man-in-warehouse-with-mask.jpg'],
        ['uploads/pages/819382798networksecurity.jpg', 'assets/images/pages/network-security.jpg'],
        ['uploads/pages/1860828277Firewall.jpg', 'assets/images/pages/hardware-firewalls.jpg'],
        ['uploads/pages/01M0R8QG5A67A22MQ1VKWXAB9F.jpg', 'assets/images/pages/apple-page.jpg'],
        ['uploads/pages/01M1H3G6W6TY70YEFVMA78VXSP.jpg', 'assets/images/pages/title-page.jpg'],
    ];

    foreach ($moves as $index => [$legacyPath]) {
        graphicsPageFixture([
            'name' => 'Legacy page '.$index,
            'link' => 'legacy-page-'.$index,
            'image' => $legacyPath,
        ]);
    }

    $migration = include database_path('migrations/2026_11_13_135744_move_page_images_to_tracked_assets.php');
    $migration->up();

    foreach ($moves as $index => [, $trackedPath]) {
        expect(Page::where('link', 'legacy-page-'.$index)->value('image'))->toBe($trackedPath);
    }

    foreach ($moves as [, $trackedPath]) {
        expect(public_path($trackedPath))->toBeFile();
    }
});
