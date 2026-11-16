<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Restores the Guest Appearances feed and its thumbnails (testing report 021).
 *
 * The thumbnails lived in uploads/radio_show, which is not committed, so the
 * deployed site rendered guest entries without images. They now point at
 * tracked assets. Entries that never had a thumbnail in the original data get
 * the shared guest-appearance image instead of a broken `src`.
 */
return new class extends Migration
{
    private const LINK = 'shows-i-appeared-as-a-guest-on';

    private const TITLE = 'Shows I appeared as a Guest on';

    public function up(): void
    {
        $categoryId = DB::table('category_radio_show')->where('link', self::LINK)->value('id');

        if (! $categoryId) {
            $categoryId = DB::table('category_radio_show')->insertGetId([
                'title' => self::TITLE,
                'menu_status' => 1,
                'parent_id' => 0,
                'sub_title' => 'Guest appearances on TV, radio, and podcast shows',
                'description' => '<p>John C. Morley shares technology and business insights as a guest on TV, radio, and podcast shows.</p>',
                'image' => '',
                'link' => self::LINK,
                'published' => '2021-01-01 00:00:00',
            ]);
        }

        foreach (self::entries() as $entry) {
            $show = DB::table('radio_show')->where('link', $entry['link'])->first();

            if ($show) {
                DB::table('radio_show')
                    ->where('id', $show->id)
                    ->update(['image' => $entry['image']]);

                continue;
            }

            DB::table('radio_show')->insert([
                'link' => $entry['link'],
                'name' => $entry['name'],
                'description' => $entry['description'],
                'show_date' => $entry['show_date'],
                'category_id' => $categoryId,
                'image' => $entry['image'],
                'published' => $entry['show_date'].' 00:00:00',
                'meta_title' => $entry['name'],
                'meta_keywords' => 'guest appearance, podcast, radio show, TV show, John C. Morley',
                'meta_description' => strip_tags($entry['description']),
            ]);
        }
    }

    public function down(): void
    {
        foreach (self::entries() as $entry) {
            DB::table('radio_show')
                ->where('link', $entry['link'])
                ->where('description', $entry['description'])
                ->delete();
        }

        DB::table('category_radio_show')
            ->where('link', self::LINK)
            ->where('description', '<p>John C. Morley shares technology and business insights as a guest on TV, radio, and podcast shows.</p>')
            ->delete();
    }

    /**
     * @return array<int, array<string, string>>
     */
    private static function entries(): array
    {
        return [
            [
                'link' => 'the-misfitnation-feb-15-2022',
                'name' => 'The MisFitNation Feb 15, 2022',
                'show_date' => '2022-03-03',
                'image' => 'assets/images/guest-appearances/the-misfitnation-feb-15-2022.png',
                'description' => '<p>John C. Morley appeared as a guest on The MisFitNation.</p>',
            ],
            [
                'link' => 'the-kaj-master-class-streamed-jan-28-2022',
                'name' => 'The KAJ Master Class:  Streamed Jan 28, 2022',
                'show_date' => '2022-02-06',
                'image' => 'assets/images/guest-appearances/the-kaj-master-class-streamed-jan-28-2022.jpg',
                'description' => '<p>John C. Morley appeared as a guest on The KAJ Master Class.</p>',
            ],
            [
                'link' => 'wytv7-what-s-your-story-sharing-a-positive-message-in-2022-jan-20-2022',
                'name' => "WYTV7 What's your Story:  Sharing a Positive Message in 2022:  Jan 20, 2022",
                'show_date' => '2022-01-30',
                'image' => 'assets/images/guest-appearances/wytv7-what-s-your-story-sharing-a-positive-message-in-2022-jan-20-2022.jpg',
                'description' => '<p>John C. Morley appeared as a guest on WYTV7 What\'s your Story.</p>',
            ],
            [
                'link' => 'shock-your-potential-we-give-your-business-a-voice',
                'name' => 'Shock Your Potential:  We Give Your Business a Voice',
                'show_date' => '2022-01-16',
                'image' => 'assets/images/guest-appearances/shock-your-potential-we-give-your-business-a-voice.jpg',
                'description' => '<p>John C. Morley appeared as a guest on Shock Your Potential.</p>',
            ],
            [
                'link' => 'sheila-mac-show-april-27-2021',
                'name' => 'Sheila Mac Show April 27, 2021',
                'show_date' => '2021-05-15',
                'image' => 'assets/images/guest-appearances/sheila-mac-show-april-27-2021.jpg',
                'description' => '<p>John C. Morley appeared as a guest on the Sheila Mac Show.</p>',
            ],
            [
                'link' => 'the-dark-horse-entrepreneur-you-have-to-be-open-in-your-entrepreneurial-journey',
                'name' => 'The Dark Horse Entrepreneur:  You Have To-Be Open In Your Entrepreneurial Journey',
                'show_date' => '2021-04-17',
                'image' => 'assets/images/guest-appearances/the-dark-horse-entrepreneur-you-have-to-be-open-in-your-entrepreneurial-journey.jpg',
                'description' => '<p>John C. Morley appeared as a guest on The Dark Horse Entrepreneur.</p>',
            ],
            [
                'link' => 'connectionmachine-biz',
                'name' => 'Connectionmachine.biz',
                'show_date' => '2021-02-19',
                'image' => 'assets/images/guest-appearances/generic-guest-appearance.jpg',
                'description' => '<p>John C. Morley appeared as a guest on Connectionmachine.biz.</p>',
            ],
            [
                'link' => 'keepin-it-real-w-caramel',
                'name' => 'Keepin It Real w/Caramel',
                'show_date' => '2021-02-10',
                'image' => 'assets/images/guest-appearances/keepin-it-real-w-caramel.jpg',
                'description' => '<p>John C. Morley appeared as a guest on Keepin It Real w/Caramel.</p>',
            ],
            [
                'link' => 'mike-kara-radio-program-jan-16-2021',
                'name' => 'Mike Kara Radio Program:  Jan 16, 2021',
                'show_date' => '2021-01-20',
                'image' => 'assets/images/guest-appearances/mike-kara-radio-program-jan-16-2021.jpg',
                'description' => '<p>John C. Morley appeared as a guest on the Mike Kara Radio Program.</p>',
            ],
            [
                'link' => 'the-world-of-ability-jan-9-2021',
                'name' => 'The World Of Ability Jan 9, 2021',
                'show_date' => '2021-01-16',
                'image' => 'assets/images/guest-appearances/the-world-of-ability-jan-9-2021.jpg',
                'description' => '<p>John C. Morley appeared as a guest on The World Of Ability.</p>',
            ],
            [
                'link' => 'pr-360',
                'name' => 'PR 360',
                'show_date' => '2022-01-21',
                'image' => 'assets/images/guest-appearances/generic-guest-appearance.jpg',
                'description' => '<p>John C. Morley appeared as a guest on PR 360.</p>',
            ],
            [
                'link' => 'my-future-business-show',
                'name' => 'My Future Business Show',
                'show_date' => '2021-01-05',
                'image' => 'assets/images/guest-appearances/generic-guest-appearance.jpg',
                'description' => '<p>John C. Morley appeared as a guest on My Future Business Show.</p>',
            ],
        ];
    }
};
