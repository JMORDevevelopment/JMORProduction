<?php

use App\Models\News;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;

beforeEach(function () {
    $this->withoutMiddleware(PreventRequestForgery::class);

    News::create([
        'name' => 'JMOR News Item',
        'link' => 'jmor-news-item',
        'priority' => 1,
        'type' => 'general',
        'description' => '<p>News description content</p>',
        'image' => 'uploads/news/test.jpg',
        'published' => '2020-01-01 00:00:00',
    ]);

    foreach (range(2, 6) as $i) {
        News::create([
            'name' => "News Item {$i}",
            'link' => "news-item-{$i}",
            'priority' => 1,
            'type' => 'general',
            'description' => '<p>News description content</p>',
            'image' => 'uploads/news/test.jpg',
            'published' => "2020-01-0{$i} 00:00:00",
        ]);
    }
});

test('the news listing page shows its posts', function () {
    $this->get(route('news'))
        ->assertSuccessful()
        ->assertSee('News')
        ->assertSee('News Item 6')
        ->assertSee('News Item 2');
});

test('the news detail page renders its post by link', function () {
    $this->get(route('news.detail', 'jmor-news-item'))
        ->assertSuccessful()
        ->assertSee('News description content');
});

test('unknown news links return a 404', function () {
    $this->get(route('news.detail', 'does-not-exist'))->assertNotFound();
});

test('the news listing paginates after five items', function () {
    $this->get(route('news'))->assertSuccessful()->assertSee('News Item 6')->assertSee('News Item 2');
    $this->get(route('news.index', ['start' => 5]))->assertSuccessful()->assertSee('News Item 6')->assertSee('Showing 6 to 6 from 6 items');
});
