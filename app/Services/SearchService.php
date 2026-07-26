<?php

namespace App\Services;

<<<<<<< HEAD
use App\Models\Blog;
use App\Models\Page;
use App\Models\RadioShow;
=======
use Illuminate\Support\Facades\DB;
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)

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

<<<<<<< HEAD
        $blogs = Blog::where('name', 'like', '%'.$searchKey.'%')->limit(10)->get()->all();
        $pages = Page::where('name', 'like', '%'.$searchKey.'%')->limit(10)->get()->all();
=======
        $blogs = DB::table('blog')->where('name', 'like', '%'.$searchKey.'%')->limit(10)->get()->toArray();
        $pages = DB::table('pages')->where('name', 'like', '%'.$searchKey.'%')->limit(10)->get()->toArray();
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)

        return array_merge($blogs, $pages);
    }

    /**
     * Search radio shows by name and/or air date.
     */
    public function searchRadioShows(?string $searchKey, ?string $dateKey): array
    {
<<<<<<< HEAD
        return RadioShow::when(! empty($dateKey), function ($query) use ($dateKey) {
                $query->where('show_date', date('Y-m-d', strtotime($dateKey)));
            })
            ->when(! empty($searchKey), function ($query) use ($searchKey) {
                $query->where('name', 'like', '%'.$searchKey.'%');
            })
            ->get()
            ->all();
=======
        $query = DB::table('radio_show');

        if (! empty($dateKey)) {
            $showDate = date('Y-m-d', strtotime($dateKey));
            $query->where('show_date', $showDate);
        }

        if (! empty($searchKey)) {
            $query->where('name', 'like', '%'.$searchKey.'%');
        }

        return $query->get()->toArray();
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
    }
}
