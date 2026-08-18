<?php

use App\Models\BrandGuideline;
use App\Models\MediaResource;
use App\Models\MediaVideo;
use App\Models\PressRelease;

beforeEach(function () {
    MediaResource::create([
        'name' => 'JMOR In The Spotlight',
        'link' => 'jmor-in-the-spotlight',
        'description' => '<p>Media resource description content</p>',
        'image' => 'uploads/media_resouces/test.jpg',
        'published' => '2020-01-01 00:00:00',
        'meta_title' => '',
        'meta_keywords' => '',
        'meta_description' => '',
    ]);

    MediaVideo::create([
        'name' => 'Tech Talk Show Episode 1',
        'link' => 'tech-talk-show-episode-1',
        'description' => '<p>Media video description content</p>',
        'video_link' => 'https://youtube.com/watch?v=test',
        'published' => '2020-01-01 00:00:00',
        'meta_title' => '',
        'meta_keywords' => '',
        'meta_description' => '',
    ]);

    PressRelease::create([
        'name' => 'Tech Expert YouTube Channel',
        'link' => 'tech-expert-youtube-channel',
        'description' => '<p>Press release description content</p>',
        'image' => 'uploads/press_releases/test.jpg',
        'published' => '2020-01-01 00:00:00',
        'meta_title' => '',
        'meta_keywords' => '',
        'meta_description' => '',
    ]);

    BrandGuideline::create([
        'name' => 'JMOR Brand Guidelines',
        'link' => 'jmor-brand-guidelines',
        'description' => '<p>Brand guideline description content</p>',
        'image' => 'uploads/brand_guidelines/test.jpg',
        'published' => '2020-01-01 00:00:00',
        'meta_title' => '',
        'meta_keywords' => '',
        'meta_description' => '',
    ]);
});

test('media resource listing and detail pages render correctly', function () {
    $this->get(route('media-resources'))
        ->assertSuccessful()
        ->assertSee('JMOR In The Spotlight');

    $this->get(route('media-resources.detail', 'jmor-in-the-spotlight'))
        ->assertSuccessful()
        ->assertSee('Media resource description content');
});

test('media video listing and detail pages render correctly', function () {
    $this->get(route('media-video'))
        ->assertSuccessful()
        ->assertSee('Tech Talk Show Episode 1');

    $this->get(route('media-video.detail', 'tech-talk-show-episode-1'))
        ->assertSuccessful()
        ->assertSee('Media video description content');
});

test('press releases listing and detail pages render correctly', function () {
    $this->get(route('press-releases'))
        ->assertSuccessful()
        ->assertSee('Tech Expert YouTube Channel');

    $this->get(route('press-releases.detail', 'tech-expert-youtube-channel'))
        ->assertSuccessful()
        ->assertSee('Press release description content');
});

test('brand guidelines listing and detail pages render correctly', function () {
    $this->get(route('brand-guidelines'))
        ->assertSuccessful()
        ->assertSee('JMOR Brand Guidelines');

    $this->get(route('brand-guidelines.detail', 'jmor-brand-guidelines'))
        ->assertSuccessful()
        ->assertSee('Brand guideline description content');
});

test('unknown media detail links return a 404', function () {
    $this->get(route('media-resources.detail', 'does-not-exist'))->assertNotFound();
    $this->get(route('media-video.detail', 'does-not-exist'))->assertNotFound();
    $this->get(route('press-releases.detail', 'does-not-exist'))->assertNotFound();
    $this->get(route('brand-guidelines.detail', 'does-not-exist'))->assertNotFound();
});
