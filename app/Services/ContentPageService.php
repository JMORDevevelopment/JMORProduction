<?php

namespace App\Services;

<<<<<<< HEAD
use Illuminate\Database\Eloquent\Model;
=======
use Illuminate\Support\Facades\DB;
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)

/**
 * Shared logic for the "listing page + detail-by-link page" pattern used
 * by Blog, Case Studies, Events, Press Releases, Media Video/Resources,
 * Brand Guidelines, Random Acts of Kindness, and Recommended content.
 */
class ContentPageService
{
    /**
     * Fetch a single row by its `link` slug, or abort with a 404.
<<<<<<< HEAD
     *
     * @param class-string<Model> $modelClass
     */
    public function findByLink(string $modelClass, string $link): Model
    {
        $row = $modelClass::where('link', $link)->first();
=======
     */
    public function findByLink(string $table, string $link): object
    {
        $row = DB::table($table)->where('link', $link)->first();
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)

        if (! $row) {
            abort(404);
        }

        return $row;
    }

    /**
     * Build the standard meta fields (title/description/keywords) from a row.
     */
<<<<<<< HEAD
    public function metaFor(Model $row): array
=======
    public function metaFor(object $row): array
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
    {
        return [
            'title' => $row->meta_title ?: $row->name,
            'description' => strip_tags($row->meta_description ?? ''),
            'keywords' => $row->meta_keywords ?? '',
        ];
    }

    /**
     * Full view payload for a detail page: the row (under $dataKey) plus meta fields.
<<<<<<< HEAD
     *
     * @param class-string<Model> $modelClass
     */
    public function detailViewData(string $modelClass, string $link, string $dataKey): array
    {
        $row = $this->findByLink($modelClass, $link);

        return array_merge(
            [$dataKey => $row->toArray()],
=======
     */
    public function detailViewData(string $table, string $link, string $dataKey): array
    {
        $row = $this->findByLink($table, $link);

        return array_merge(
            [$dataKey => (array) $row],
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
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
