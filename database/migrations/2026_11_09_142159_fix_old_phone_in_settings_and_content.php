<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Testing report issue 002 (sitewide part): the primary phone number
     * still appears as the old (877) 767-5667 in the contact-page setting
     * (with a stray trailing character) and inside stored page content.
     * The blade templates were already corrected in an earlier commit.
     */
    public function up(): void
    {
        if (Schema::hasTable('settings')) {
            DB::statement(
                "UPDATE settings
                    SET value = '(973) 394-1000'
                    WHERE `option` = 'phone'
                      AND value LIKE '%767-5667%'"
            );
        }

        if (Schema::hasTable('pages')) {
            DB::statement(
                "UPDATE pages
                    SET description = REPLACE(
                        REPLACE(
                            description,
                            '(877) 767-5667',
                            '(973) 394-1000'
                        ),
                        '(877)767-5667',
                        '(973) 394-1000'
                    )
                    WHERE description LIKE '%767-5667%'"
            );
        }
    }

    /**
     * Content correction is intentionally not reversible.
     */
    public function down(): void
    {
        //
    }
};
