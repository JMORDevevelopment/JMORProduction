<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * The approved mission image was only available in the untracked uploads
     * directory, so it disappeared on every fresh deployment.
     */
    private const IMAGE = 'assets/images/pages/jmor-mission.jpg';

    private const PAGE_LINKS = ['about-us', 'our-mission'];

    public function up(): void
    {
        DB::table('pages')
            ->whereIn('link', self::PAGE_LINKS)
            ->where(function ($query) {
                $query->whereNull('image')->orWhere('image', '');
            })
            ->update(['image' => self::IMAGE]);
    }

    public function down(): void
    {
        DB::table('pages')
            ->whereIn('link', self::PAGE_LINKS)
            ->where('image', self::IMAGE)
            ->update(['image' => '']);
    }
};
