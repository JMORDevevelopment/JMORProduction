<?php

namespace App\Services;

use App\Models\CategoryRadioShow;
use App\Models\RadioShow;

class RadioShowService
{
    /**
     * Build the view data for a radio show category page, including its
     * child categories and every show belonging to the category tree.
     */
    public function categoryViewData(string $categoryLink, ?string $currentYear = null): array
    {
        $category = CategoryRadioShow::where('link', $categoryLink)->first();

        if (! $category) {
            abort(404);
        }

        $children = CategoryRadioShow::where('parent_id', $category->id)->get()->all();

        return [
            'category_radio_show' => [$category->toArray()],
            'top_title' => $category->title,
            'category_id' => $category->id,
            'currentYear' => $currentYear,
            'category_radio_show_child' => $children,
            'title' => $category->meta_title ?: $category->title,
            'description' => $category->meta_keywords ?? '',
            'keywords' => $category->meta_description ?? '',
            'show_datas' => $this->showsForCategory($category, $children),
            'categories' => CategoryRadioShow::orderBy('id', 'asc')->get(),
            'latestPosts' => RadioShow::where('category_id', $category->id)
                ->orderBy('id', 'desc')
                ->limit(5)
                ->get(),
        ];
    }

    /**
     * Shows directly in a category; if it's a top-level (parent_id == 0)
     * category, also includes shows from every child category.
     */
    private function showsForCategory(CategoryRadioShow $category, array $children): array
    {
        if ($category->parent_id != 0) {
            return RadioShow::where('category_id', $category->id)
                ->orderBy('id', 'desc')
                ->get()
                ->all();
        }

        $ownShows = RadioShow::where('category_id', $category->id)
            ->orderBy('id', 'desc')
            ->get()
            ->all();

        if (empty($children)) {
            return $ownShows;
        }

        $childIds = array_column($children, 'id');
        $childShows = RadioShow::whereIn('category_id', $childIds)
            ->orderBy('id', 'desc')
            ->get()
            ->all();

        return array_merge($ownShows, $childShows);
    }
}
