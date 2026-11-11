<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Testing report issue 041: the published privacy policy still carries
 * another brand's domain, an unrelated Chamber of Commerce remarketing
 * paragraph, a 2019 effective date, and agreement errors.
 */
class PrivacyPolicyContentFix
{
    public static function apply(): void
    {
        if (! Schema::hasTable('pages')) {
            return;
        }

        $page = DB::table('pages')
            ->where('link', 'privacy-policy')
            ->first();

        if ($page === null) {
            return;
        }

        $html = (string) $page->description;

        $html = str_replace('ourprintconnection.com', 'jmor.com', $html);

        // Drop the entire Behavioral Remarketing block: it credited the
        // Franklin Lakes Chamber of Commerce (wrong entity) and claimed
        // ad remarketing we do not operate. Next section starts at Do Not Track.
        $start = strpos($html, '<p><strong>Behavioral Remarketing</strong></p>');
        $end = strpos($html, '<p><strong>Do Not Track Disclosure</strong></p>');
        if ($start !== false && $end !== false && $end > $start) {
            $html = substr($html, 0, $start).substr($html, $end);
        }

        $html = str_replace(
            'This Privacy Policy is effective as of March 01, 2019',
            'This Privacy Policy is effective as of January 01, 2026',
            $html
        );

        // Subject-verb agreement in the cookie help text.
        $html = str_replace(
            'The Help feature on most browsers provide information',
            'The Help feature on most browsers provides information',
            $html
        );

        if ($html !== (string) $page->description) {
            DB::table('pages')
                ->where('id', $page->id)
                ->update(['description' => $html]);
        }
    }
}
