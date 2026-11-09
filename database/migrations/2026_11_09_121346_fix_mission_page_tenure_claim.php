<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Testing report issues 004/025: the Our Mission page claims "over
     * 28 years of experience". Corrected to 33 years as of 2026 (founded
     * 1993), matching the approved copy. Stored page HTML cannot use the
     * dynamic year calculation the blade templates use, so this content
     * needs a manual refresh in future years.
     */
    public function up(): void
    {
        if (! Schema::hasTable('pages')) {
            return;
        }

        DB::statement(
            "UPDATE pages
                SET description = REPLACE(
                    description,
                    'Having over 28 years of experience',
                    'Having over 33 years of experience'
                )
                WHERE description LIKE '%Having over 28 years%'"
        );
    }

    /**
     * Content correction is intentionally not reversible.
     */
    public function down(): void
    {
        //
    }
};
