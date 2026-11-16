<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Cache::flush();

    config([
        'services.instagram.graph_url' => 'https://graph.facebook.com/v27.0',
        'services.instagram.user_id' => null,
        'services.instagram.access_token' => null,
        'services.instagram.cache_ttl' => 1800,
    ]);
});

function instagramMediaPayload(): array
{
    return [
        'data' => [
            [
                'id' => 'media-1',
                'caption' => 'First approved caption',
                'media_type' => 'IMAGE',
                'media_url' => 'https://cdn.example.test/post-1.jpg',
                'permalink' => 'https://www.instagram.com/p/one/',
                'alt_text' => 'Alt text for the first post',
            ],
            [
                'id' => 'media-2',
                'caption' => 'Second <b>caption</b>',
                'media_type' => 'VIDEO',
                'media_url' => 'https://cdn.example.test/video.mp4',
                'thumbnail_url' => 'https://cdn.example.test/post-2.jpg',
                'permalink' => 'https://www.instagram.com/p/two/',
            ],
            [
                // Malformed entry: no image, so it must not render a blank tile.
                'id' => 'media-3',
                'media_type' => 'IMAGE',
                'permalink' => 'https://www.instagram.com/p/three/',
            ],
        ],
    ];
}

function configureInstagramFeed(): void
{
    config([
        'services.instagram.user_id' => 'ig-user-1',
        'services.instagram.access_token' => 'secret-token',
    ]);
}

test('the homepage falls back to the profile when the feed is not configured', function () {
    Http::fake();

    $this->get(route('home'))
        ->assertSuccessful()
        ->assertSee('View Instagram profile')
        ->assertSee('See the latest posts, tech tips')
        ->assertDontSee('>Post<', false)
        ->assertDontSee('jm-news__insta-grid', false);

    Http::assertNothingSent();
});

test('the configured feed renders real posts with links and accessible alt text', function () {
    configureInstagramFeed();
    Http::fake(['graph.facebook.com/*' => Http::response(instagramMediaPayload(), 200)]);

    $html = $this->get(route('home'))->assertSuccessful()->getContent();

    expect($html)
        ->toContain('https://cdn.example.test/post-1.jpg')
        ->toContain('https://www.instagram.com/p/one/')
        ->toContain('alt="Alt text for the first post"')
        // Video posts use their thumbnail and fall back to the caption for alt.
        ->toContain('https://cdn.example.test/post-2.jpg')
        ->toContain('alt="Second caption"')
        ->not->toContain('>Post<')
        ->not->toContain('Second <b>caption</b>');

    expect(substr_count($html, 'class="jm-news__insta-tile"'))->toBe(2);

    Http::assertSent(function ($request) {
        return str_contains($request->url(), '/ig-user-1/media')
            && $request['access_token'] === 'secret-token'
            && str_contains((string) $request['fields'], 'thumbnail_url')
            && str_contains((string) $request['fields'], 'alt_text');
    });
});

test('the feed is cached between requests', function () {
    configureInstagramFeed();
    Http::fake(['graph.facebook.com/*' => Http::response(instagramMediaPayload(), 200)]);

    $this->get(route('home'))->assertSuccessful();
    $this->get(route('home'))->assertSuccessful();

    Http::assertSentCount(1);
});

test('a rejected feed request renders the fallback instead of an error', function () {
    configureInstagramFeed();
    Http::fake([
        'graph.facebook.com/*' => Http::response([
            'error' => ['message' => 'Invalid OAuth access token'],
        ], 401),
    ]);

    $this->get(route('home'))
        ->assertSuccessful()
        ->assertSee('See the latest posts, tech tips')
        ->assertDontSee('jm-news__insta-grid', false);
});

test('a failing feed request renders the fallback instead of an error', function () {
    configureInstagramFeed();
    Http::fake(['graph.facebook.com/*' => Http::response('', 500)]);

    $this->get(route('home'))
        ->assertSuccessful()
        ->assertSee('View Instagram profile')
        ->assertDontSee('jm-news__insta-grid', false);
});
