<?php

use App\Models\CategoryRadioShow;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;

beforeEach(function () {
    $this->withoutMiddleware(PreventRequestForgery::class);
});

function seedRadioCategory(array $overrides = []): void
{
    CategoryRadioShow::create(array_merge([
        'title' => 'Tech Talks',
        'sub_title' => '',
        'description' => '',
        'image' => '',
        'link' => 'tech-talks',
        'menu_status' => 0,
        'parent_id' => 0,
        'published' => '2021-05-05 00:00:00',
    ], $overrides));
}

test('the year filter returns categories published on or before that year', function () {
    seedRadioCategory();
    seedRadioCategory([
        'title' => 'Future Show',
        'link' => 'future-show',
        'published' => '2026-01-15 00:00:00',
    ]);
    seedRadioCategory([
        'title' => 'Child Category',
        'link' => 'child-category',
        'parent_id' => 1,
        'published' => '2019-01-01 00:00:00',
    ]);

    $response = $this->post(route('radio.categories-list'), [
        'year' => '2024',
        '_token' => 'test',
    ]);

    $response->assertOk();
    expect($response->headers->get('Content-Type'))->toContain('text/html');

    $payload = json_decode($response->getContent(), true);
    expect($payload['msg'])->toBe('success');
    expect($payload['data'])->toContain('Tech Talks');
    expect($payload['data'])->toContain('/category-jmor-shows/tech-talks/2024');
    expect($payload['data'])->not->toContain('Future Show');
    expect($payload['data'])->not->toContain('Child Category');
});

test('the year filter reports when no category matches', function () {
    seedRadioCategory([
        'title' => 'Future Show',
        'link' => 'future-show',
        'published' => '2026-01-15 00:00:00',
    ]);

    $response = $this->post(route('radio.categories-list'), [
        'year' => '2024',
        '_token' => 'test',
    ]);

    $payload = json_decode($response->getContent(), true);
    expect($payload['data'])->toBe('No Record Found.');
});

test('the radio page links categories with a year path segment', function () {
    seedRadioCategory();

    $this->get(route('jmor-shows'))
        ->assertSuccessful()
        ->assertSee('/category-jmor-shows/tech-talks/'.date('Y'), false);
});
