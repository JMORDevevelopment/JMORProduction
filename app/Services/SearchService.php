<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

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

        $blogs = DB::table('blog')->where('name', 'like', '%'.$searchKey.'%')->limit(10)->get()->toArray();
        $pages = DB::table('pages')->where('name', 'like', '%'.$searchKey.'%')->limit(10)->get()->toArray();

        return array_merge($blogs, $pages);
    }

    /**
     * Search radio shows by name and/or air date.
     */
    public function searchRadioShows(?string $searchKey, ?string $dateKey): array
    {
        $query = DB::table('radio_show');

        if (! empty($dateKey)) {
            $showDate = date('Y-m-d', strtotime($dateKey));
            $query->where('show_date', $showDate);
        }

        if (! empty($searchKey)) {
            $query->where('name', 'like', '%'.$searchKey.'%');
        }

        return $query->get()->toArray();
    }
}
