<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

/**
 * Shared logic for the "listing page + detail-by-link page" pattern used
 * by Blog, Case Studies, Events, Press Releases, Media Video/Resources,
 * Brand Guidelines, Random Acts of Kindness, and Recommended content.
 */
class ContentPageService
{
    /**
     * Fetch a single row by its `link` slug, or abort with a 404.
     */
    public function findByLink(string $table, string $link): object
    {
        $row = DB::table($table)->where('link', $link)->first();

        if (! $row) {
            abort(404);
        }

        return $row;
    }

    /**
     * Build the standard meta fields (title/description/keywords) from a row.
     */
    public function metaFor(object $row): array
    {
        return [
            'title' => $row->meta_title ?: $row->name,
            'description' => strip_tags($row->meta_description ?? ''),
            'keywords' => $row->meta_keywords ?? '',
        ];
    }

    /**
     * Full view payload for a detail page: the row (under $dataKey) plus meta fields.
     */
    public function detailViewData(string $table, string $link, string $dataKey): array
    {
        $row = $this->findByLink($table, $link);

        return array_merge(
            [$dataKey => (array) $row],
            $this->metaFor($row)
        );
    }

    /**
     * View payload for a plain listing page with a static title.
     */
    public function listingViewData(string $title): array
    {
        return [
            'title' => $title,
            'description' => '',
            'keywords' => '',
        ];
    }
}
