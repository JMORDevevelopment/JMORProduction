<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Testing report issue 040: add genuinely useful internal links
     * where natural on substantive pages. Five anchors were chosen
     * because they already describe the linked service; each target is
     * a live beta page (not a coming-soon placeholder). Each phrase
     * occurs exactly once in its page.
     */
    public function up(): void
    {
        if (! Schema::hasTable('pages')) {
            return;
        }

        $links = [
            ['why-choose-jmor', 'computers and networking systems', '/it-tech-support-services-in-nj'],
            ['why-choose-jmor', 'best solutions available', '/custom-built-solutions'],
            ['it-tech-support-services-in-nj', 'managed network support services', '/network-cyber-security-internet-safety'],
            ['custom-built-solutions', 'full line of products and services', '/it-tech-support-services-in-nj'],
            ['our-mission', 'keep them up and running', '/it-tech-support-services-in-nj'],
        ];

        foreach ($links as [$pageLink, $anchor, $target]) {
            DB::statement(
                'UPDATE pages
                    SET description = REPLACE(
                        description,
                        :anchor,
                        :replacement
                    )
                    WHERE link = :page_link
                      AND description LIKE :pattern',
                [
                    'anchor' => $anchor,
                    'replacement' => '<a href="'.$target.'">'.$anchor.'</a>',
                    'page_link' => $pageLink,
                    'pattern' => '%'.$anchor.'%',
                ]
            );
        }
    }

    /**
     * Content additions are intentionally not reversible.
     */
    public function down(): void
    {
        //
    }
};
