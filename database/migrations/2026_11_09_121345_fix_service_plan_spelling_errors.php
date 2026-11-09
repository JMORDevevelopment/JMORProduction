<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Testing report issue 029: spelling errors in the published service
     * plan bullet lists (maintenence -> maintenance, disgnostics ->
     * diagnostics). Content lives in home_tab.tab_list as a JSON array,
     * so plain string replacement keeps the structure intact.
     */
    public function up(): void
    {
        if (! Schema::hasTable('home_tab')) {
            return;
        }

        DB::statement(
            "UPDATE home_tab
                SET tab_list = REPLACE(
                    REPLACE(tab_list, 'maintenence', 'maintenance'),
                    'disgnostics', 'diagnostics'
                )
                WHERE tab_list LIKE '%maintenence%'
                   OR tab_list LIKE '%disgnostics%'"
        );
    }

    /**
     * Typo corrections are intentionally not reversible.
     */
    public function down(): void
    {
        //
    }
};
