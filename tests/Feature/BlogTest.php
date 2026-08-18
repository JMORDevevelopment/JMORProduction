<?php

use App\Models\Blog;

beforeEach(function () {
    Blog::create([
        'name' => 'Older Post',
        'link' => 'blog/older-post',
        'description' => '<p>Older description content</p>',
        'image' => 'uploads/blog/older.jpg',
        'published' => '2020-01-01 00:00:00',
        'meta_title' => '',
        'meta_keywords' => '',
        'meta_description' => '',
    ]);

    Blog::create([
        'name' => 'Newer Post',
        'link' => 'blog/newer-post',
        'description' => '<p>Newer description content</p>',
        'image' => 'uploads/blog/newer.jpg',
        'published' => '2021-06-01 00:00:00',
        'meta_title' => 'Newer Post Meta',
        'meta_keywords' => 'newer, post',
        'meta_description' => 'Newer post meta description',
    ]);
});

test('the blog listing page shows posts ordered by published date', function () {
    $this->get(route('blog'))
        ->assertSuccessful()
        ->assertSee('Blog')
        ->assertSee('Newer Post')
        ->assertSee('Older Post');
});

test('the blog detail page shows a single post by slug', function () {
    $this->get(route('blog.detail', 'older-post'))
        ->assertSuccessful()
        ->assertSee('Older Post')
        ->assertSee('Older description content');
});

test('an unknown blog slug returns a 404', function () {
    $this->get(route('blog.detail', 'does-not-exist'))->assertNotFound();
});

test('the legacy duplicated-path blog URL redirects to the detail page', function () {
    $this->get('/blog/blog/older-post')->assertRedirect(route('blog.detail', 'older-post'));
});
