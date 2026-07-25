<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class RadioShowService
{
    /**
     * Build the view data for a radio show category page, including its
     * child categories and every show belonging to the category tree.
     */
    public function categoryViewData(string $categoryLink, ?string $currentYear = null): array
    {
        $category = DB::table('category_radio_show')->where('link', $categoryLink)->first();

        if (! $category) {
            abort(404);
        }

        $children = DB::table('category_radio_show')->where('parent_id', $category->id)->get()->toArray();

        return [
            'category_radio_show' => [(array) $category],
            'top_title' => $category->title,
            'category_id' => $category->id,
            'currentYear' => $currentYear,
            'category_radio_show_child' => $children,
            'title' => $category->meta_title ?: $category->title,
            'description' => $category->meta_keywords ?? '',
            'keywords' => $category->meta_description ?? '',
            'show_datas' => $this->showsForCategory($category, $children),
        ];
    }

    /**
     * Shows directly in a category; if it's a top-level (parent_id == 0)
     * category, also includes shows from every child category.
     */
    private function showsForCategory(object $category, array $children): array
    {
        if ($category->parent_id != 0) {
            return DB::table('radio_show')
                ->where('category_id', $category->id)
                ->orderBy('id', 'desc')
                ->get()
                ->toArray();
        }

        $ownShows = DB::table('radio_show')
            ->where('category_id', $category->id)
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();

        if (empty($children)) {
            return $ownShows;
        }

        $childIds = array_column($children, 'id');
        $childShows = DB::table('radio_show')
            ->whereIn('category_id', $childIds)
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();

        return array_merge($ownShows, $childShows);
    }
}
