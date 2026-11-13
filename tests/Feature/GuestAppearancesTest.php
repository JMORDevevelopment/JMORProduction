<?php

use Illuminate\Support\Facades\DB;

const GUEST_APPEARANCES_LINK = 'shows-i-appeared-as-a-guest-on';

function runGuestAppearancesMigration(): void
{
    $migration = include database_path('migrations/2026_11_13_160000_restore_guest_appearance_images.php');
    $migration->up();
}

test('the guest appearances feed lists every restored appearance', function (string $name) {
    $this->get('/shows-i-appeared-as-a-guest-on')
        ->assertSuccessful()
        ->assertSee($name);
})->with([
    'The MisFitNation Feb 15, 2022',
    'The KAJ Master Class:  Streamed Jan 28, 2022',
    "WYTV7 What's your Story:  Sharing a Positive Message in 2022:  Jan 20, 2022",
    'Shock Your Potential:  We Give Your Business a Voice',
    'Sheila Mac Show April 27, 2021',
    'The Dark Horse Entrepreneur:  You Have To-Be Open In Your Entrepreneurial Journey',
    'Connectionmachine.biz',
    'Keepin It Real w/Caramel',
    'Mike Kara Radio Program:  Jan 16, 2021',
    'The World Of Ability Jan 9, 2021',
    'PR 360',
    'My Future Business Show',
]);

test('every guest appearance renders its tracked image', function () {
    $response = $this->get('/shows-i-appeared-as-a-guest-on')->assertSuccessful();

    $images = DB::table('radio_show')
        ->join('category_radio_show', 'category_radio_show.id', '=', 'radio_show.category_id')
        ->where('category_radio_show.link', GUEST_APPEARANCES_LINK)
        ->pluck('radio_show.image');

    expect($images)->not->toBeEmpty();

    foreach ($images as $image) {
        expect($image)->not->toBeEmpty()
            ->and($image)->toStartWith('assets/images/guest-appearances/')
            ->and(public_path($image))->toBeFile();

        $response->assertSee('src="'.asset($image).'"', false);
    }
});

test('no guest appearance falls back to a bare site url as its image', function () {
    $response = $this->get('/shows-i-appeared-as-a-guest-on')->assertSuccessful();

    $response->assertDontSee('src="'.url('/').'"', false);
});

test('guest appearance detail pages render their image', function () {
    $this->get('/jmor-shows/pr-360')
        ->assertSuccessful()
        ->assertSee('src="'.asset('assets/images/guest-appearances/generic-guest-appearance.jpg').'"', false);
});

test('the guest appearances migration is idempotent', function () {
    runGuestAppearancesMigration();
    runGuestAppearancesMigration();

    expect(DB::table('category_radio_show')->where('link', GUEST_APPEARANCES_LINK)->count())->toBe(1);

    foreach (['pr-360', 'the-misfitnation-feb-15-2022', 'my-future-business-show'] as $link) {
        expect(DB::table('radio_show')->where('link', $link)->count())->toBe(1);
    }
});

test('the migration replaces a legacy untracked guest thumbnail', function () {
    DB::table('radio_show')
        ->where('link', 'pr-360')
        ->update(['image' => 'uploads/radio_show/legacy.jpg']);

    runGuestAppearancesMigration();

    expect(DB::table('radio_show')->where('link', 'pr-360')->value('image'))
        ->toBe('assets/images/guest-appearances/generic-guest-appearance.jpg');
});
