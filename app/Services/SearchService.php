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

        $blogs = Blog::query()->where('name', 'like', '%'.$searchKey.'%')->limit(10)->get()->all();
        $pages = Page::query()->where('name', 'like', '%'.$searchKey.'%')->limit(10)->get()->all();

        return array_merge($blogs, $pages);
    }

    /**
     * Search radio shows by name and/or air date.
     */
    public function searchRadioShows(?string $searchKey, ?string $dateKey): array
    {
        $query = RadioShow::query();

        if (! empty($dateKey)) {
            $showDate = date('Y-m-d', strtotime($dateKey));
            $query->where('show_date', $showDate);
        }

        if (! empty($searchKey)) {
            $query->where('name', 'like', '%'.$searchKey.'%');
        }

        return $query->get()->all();
    }
}
