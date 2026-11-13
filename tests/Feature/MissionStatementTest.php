<?php

use App\Models\Page;

const MISSION_MIGRATION = '2026_11_13_163000_refresh_mission_statement.php';

const LEGACY_MISSION_COPY = '<p><strong>Mission Statement:</strong></p>
<p>The JMOR Connection, Inc. is dedicated to providing solutions and services that keep your business up and running smoothly.</p>
<p><strong>A little about us.</strong></p>
<p>Having over 28 years of experience, we care more about helping our clients solve their issues over whether or not we are making money.</p>';

function setMissionCopy(string $description): void
{
    Page::updateOrCreate(
        ['link' => 'our-mission'],
        [
            'name' => 'Our Mission',
            'priority' => 0,
            'slider_status' => 0,
            'menu_location' => 0,
            'description' => $description,
            'image' => 'assets/images/pages/jmor-mission.jpg',
            'form_id' => 0,
            'menu_status' => 0,
            'meta_title' => '',
            'meta_keywords' => '',
            'meta_description' => '',
        ]
    );
}

function runMissionMigration(): void
{
    $migration = include database_path('migrations/'.MISSION_MIGRATION);
    $migration->up();
}

test('the mission page uses current tenure language and edited copy', function () {
    setMissionCopy(LEGACY_MISSION_COPY);
    runMissionMigration();

    $this->get('/our-mission')
        ->assertSuccessful()
        ->assertSee('nearly three decades of experience')
        ->assertSee('since 1997')
        ->assertSee('than about whether we are making money')
        ->assertSee('stop the insanity of doing the same thing')
        ->assertDontSee('over 28 years')
        ->assertDontSee('28+ years');
});

test('the homepage no longer shows dated tenure references', function () {
    $this->get(route('home'))
        ->assertSuccessful()
        ->assertSee('Since 1997')
        ->assertSee('Serving New Jersey homes and businesses since 1997')
        ->assertDontSee('28+ years')
        ->assertDontSee('over 28 years')
        ->assertDontSee('30+ years');
});

test('the mission migration also refreshes the earlier "33 years" rewrite', function () {
    setMissionCopy('<p>Having over 33 years of experience, we care more about helping our clients solve their issues over whether or not we are making money.</p>');

    runMissionMigration();

    expect(Page::where('link', 'our-mission')->value('description'))
        ->toContain('nearly three decades of experience')
        ->not->toContain('33 years');
});

test('the mission migration preserves copy an editor has already rewritten', function () {
    setMissionCopy('<p>Our own refreshed mission copy.</p>');

    runMissionMigration();

    expect(Page::where('link', 'our-mission')->value('description'))
        ->toBe('<p>Our own refreshed mission copy.</p>');
});

test('the mission migration restores a missing mission page', function () {
    Page::where('link', 'our-mission')->delete();

    runMissionMigration();

    $page = Page::where('link', 'our-mission')->first();

    expect($page)->not->toBeNull()
        ->and($page->description)->toContain('nearly three decades of experience');
});

test('the mission migration is idempotent', function () {
    setMissionCopy(LEGACY_MISSION_COPY);

    runMissionMigration();
    runMissionMigration();

    expect(Page::where('link', 'our-mission')->count())->toBe(1)
        ->and(Page::where('link', 'our-mission')->value('description'))
        ->toContain('nearly three decades of experience');
});
