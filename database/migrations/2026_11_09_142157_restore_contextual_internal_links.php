<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Testing report issue 012: contextual internal links from the
     * original site content still point at production URLs that do not
     * exist on the beta. Rewritten to the migrated beta destinations.
     * Verified against the May 2026 production dump: no other internal
     * deep links exist in the original content.
     */
    public function up(): void
    {
        if (Schema::hasTable('pages')) {
            DB::statement(
                "UPDATE pages
                    SET description = REPLACE(
                        description,
                        'https://www.jmor.com/solutions/nj-it-tech-support-services/backup/',
                        '/service/it-tech-support'
                    )
                    WHERE link = 'it-tech-support-services-in-nj'
                      AND description LIKE '%jmor.com/solutions/nj-it-tech-support-services/backup%'"
            );
        }

        if (Schema::hasTable('press_releases')) {
            DB::statement(
                "UPDATE press_releases
                    SET description = REPLACE(
                        description,
                        'href=\"https://jmor.com/\"',
                        'href=\"/\"'
                    )
                    WHERE description LIKE '%href=\"https://jmor.com/\"%'"
            );
        }
    }

    /**
     * Link corrections are intentionally not reversible.
     */
    public function down(): void
    {
        //
    }
};
