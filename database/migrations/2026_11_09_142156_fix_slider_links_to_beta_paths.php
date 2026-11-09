<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Testing report issue 031: the homepage specialist-service carousel
     * ("View details" buttons) links to the production jmor.com URLs.
     * Rewritten to the equivalent internal beta paths. One legacy row
     * (network-cyber-security-internet) has no beta page under that
     * exact path, so it points at the migrated network cyber security
     * page instead.
     */
    public function up(): void
    {
        if (! Schema::hasTable('slider')) {
            return;
        }

        DB::statement(
            "UPDATE slider
                SET slider_link = REPLACE(
                    REPLACE(slider_link, 'https://jmor.com', ''),
                    'http://jmor.com', ''
                )
                WHERE slider_link LIKE '%jmor.com%'"
        );

        DB::statement(
            "UPDATE slider
                SET slider_link = '/network-cyber-security-internet-safety'
                WHERE slider_link = '/network-cyber-security-internet'"
        );
    }

    /**
     * Link corrections are intentionally not reversible.
     */
    public function down(): void
    {
        //
    }
};
