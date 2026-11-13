<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Cleans the published service plan content (testing report 035).
 *
 * The CMS carried a leftover test tab that the homepage advertised as a fifth
 * plan, plus typos and copy that named the wrong plan. Prices and inclusions
 * themselves are left untouched: they are business-approved figures that must
 * be confirmed by the client rather than edited here.
 */
return new class extends Migration
{
    private const TEST_TITLE = 'asd';

    /** @var array<string, string> */
    private const CORRECTIONS = [
        'patchesasd' => 'patches',
        'maintenence' => 'maintenance',
        'disgnostics' => 'diagnostics',
        'Your Business is an important JMOR as it is to you.' => 'Your business is as important to JMOR as it is to you.',
    ];

    public function up(): void
    {
        DB::table('home_tab')
            ->where('tab_title', self::TEST_TITLE)
            ->where('cost', 'like', '%12%')
            ->delete();

        DB::table('home_tab')->orderBy('tab_id')->each(function (object $tab): void {
            $updates = [
                'tab_description' => $this->correctText((string) $tab->tab_description, (string) $tab->tab_title),
                'tab_list' => $this->correctJson((string) $tab->tab_list),
                'benefits' => $this->correctJson((string) $tab->benefits),
            ];

            if ($updates['tab_description'] === (string) $tab->tab_description
                && $updates['tab_list'] === (string) $tab->tab_list
                && $updates['benefits'] === (string) $tab->benefits) {
                return;
            }

            DB::table('home_tab')->where('tab_id', $tab->tab_id)->update($updates);
        });
    }

    public function down(): void
    {
        // Copy corrections are not reverted; the removed test tab stays removed.
    }

    private function correctText(string $text, string $title): string
    {
        $text = str_replace(array_keys(self::CORRECTIONS), array_values(self::CORRECTIONS), $text);

        // Only the plans whose copy was pasted from the Business Manager page
        // need their name corrected; the Business Manager page itself is right.
        if ($title !== '' && ! str_contains($title, 'Business Manager')) {
            $text = str_replace('With the JMOR Business Manager plan', 'With the '.$title.' plan', $text);
        }

        return $text;
    }

    private function correctJson(string $json): string
    {
        $items = json_decode($json, true);

        if (! is_array($items)) {
            return $json;
        }

        $corrected = array_map(
            fn ($item) => is_string($item) ? $this->correctText($item, '') : $item,
            $items
        );

        return json_encode($corrected, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: $json;
    }
};
