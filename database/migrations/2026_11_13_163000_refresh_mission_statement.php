<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Rewrites the mission statement (testing report 028).
 *
 * The published copy still claimed "over 28 years"; the approved facts are a
 * 1993 founding and 33 years of experience as of 2026. The stored HTML cannot
 * evaluate Blade, so the year count is calculated when the migration runs.
 * The same pass fixes the grammar and tightens the messaging.
 */
return new class extends Migration
{
    private const LINK = 'our-mission';

    private const FOUNDING_YEAR = 1993;

    /**
     * The earlier branding batch rewrote the same sentence to "over 33
     * years"; whichever migration runs second must still refresh the copy
     * rather than silently skipping it.
     */
    private const LEGACY_MARKERS = ['over 28 years', 'over 33 years'];

    public function up(): void
    {
        $page = DB::table('pages')->where('link', self::LINK)->first();

        if (! $page) {
            DB::table('pages')->insert([
                'link' => self::LINK,
                'name' => 'Our Mission',
                'priority' => 0,
                'slider_status' => 0,
                'menu_location' => 0,
                'description' => self::content(),
                'image' => 'assets/images/pages/jmor-mission.jpg',
                'form_id' => 0,
                'meta_title' => 'Our Mission | The JMOR Connection, Inc.',
                'meta_keywords' => 'JMOR mission, IT support New Jersey, technology partner, since 1993',
                'meta_description' => 'The JMOR Connection, Inc. has served New Jersey homes and businesses since 1993 with technology solutions that keep them running.',
                'menu_status' => 0,
            ]);

            return;
        }

        $description = (string) $page->description;

        $isLegacyCopy = collect(self::LEGACY_MARKERS)
            ->contains(fn (string $marker): bool => str_contains($description, $marker));

        if (! $isLegacyCopy) {
            return;
        }

        DB::table('pages')->where('id', $page->id)->update([
            'description' => self::content(),
        ]);
    }

    public function down(): void
    {
        DB::table('pages')
            ->where('link', self::LINK)
            ->where('description', self::content())
            ->delete();
    }

    private static function content(): string
    {
        $years = now()->year - self::FOUNDING_YEAR;

        return str_replace('{{years}}', (string) $years, <<<'HTML'
<p><strong>Mission Statement</strong></p>
<p>The JMOR Connection, Inc. is dedicated to providing solutions and services that keep your business running smoothly. We bring you the latest and most reliable technology available, and we solve your problem right the first time.</p>
<p>&nbsp;</p>
<p><strong>A little about us</strong></p>
<p>Having over {{years}} years of experience, we care more about helping our clients solve their issues than about whether we are making money. We believe in security, trust, integrity, and professionalism. We understand the challenges of running a day-to-day service business, and we are not afraid to be different or to think outside the box. Our clients get the facts from us, so they can stop the insanity of doing the same thing over and over again and expecting different results. We are here to serve our community, and we have been doing so since 1993.</p>
<p>&nbsp;</p>
<p>Our clients and our community come first. We want them to know that we are here today, tomorrow, and for their future technology needs. We ask every client the same question: are you willing to sacrifice quality and service for price?</p>
<p>&nbsp;</p>
<p>The JMOR Connection, Inc. is dedicated to providing solutions and services that help small businesses grow and keep them up and running.</p>
HTML);
    }
};
