<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

/**
 * Shared logic for the "listing page + detail-by-link page" pattern used
 * by Blog, Case Studies, Events, Press Releases, Media Video/Resources,
 * Brand Guidelines, Random Acts of Kindness, and Recommended content.
 */
class ContentPageService
{
    /**
     * Fetch a single row by its `link` slug, or abort with a 404.
     *
     * @param class-string<Model> $modelClass
     */
    public function findByLink(string $modelClass, string $link): Model
    {
        $row = $modelClass::query()->where('link', $link)->first();

        if (! $row) {
            abort(404);
        }

        return $row;
    }

    /**
     * Build the standard meta fields (title/description/keywords) from a row.
     */
    public function metaFor(Model $row): array
    {
        return [
            'title' => $row->meta_title ?: $row->name,
            'description' => strip_tags($row->meta_description ?? ''),
            'keywords' => $row->meta_keywords ?? '',
        ];
    }

    /**
     * Full view payload for a detail page: the row (under $dataKey) plus meta fields.
     *
     * @param class-string<Model> $modelClass
     */
    public function detailViewData(string $modelClass, string $link, string $dataKey): array
    {
        $row = $this->findByLink($modelClass, $link);

        return array_merge(
            [$dataKey => $row->toArray()],
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
