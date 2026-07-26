<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\Page;
use App\Models\RadioShow;

class SearchService
{
    /**
     * Search blog posts and pages by name, merged into one result set.
     */
    public function searchContent(?string $searchKey): array
    {
        if (empty($searchKey)) {
            return [];
        }

        $blogs = Blog::where('name', 'like', '%'.$searchKey.'%')->limit(10)->get()->all();
        $pages = Page::where('name', 'like', '%'.$searchKey.'%')->limit(10)->get()->all();

        return array_merge($blogs, $pages);
    }

    /**
     * Search radio shows by name and/or air date.
     */
    public function searchRadioShows(?string $searchKey, ?string $dateKey): array
    {
        return RadioShow::when(! empty($dateKey), function ($query) use ($dateKey) {
                $query->where('show_date', date('Y-m-d', strtotime($dateKey)));
            })
            ->when(! empty($searchKey), function ($query) use ($searchKey) {
                $query->where('name', 'like', '%'.$searchKey.'%');
            })
            ->get()
            ->all();
    }
}
