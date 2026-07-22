<?php

namespace App\Http\Controllers;

use App\Services\SearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct(private SearchService $search)
    {
    }

    public function content(Request $request)
    {
        $results = $this->search->searchContent($request->input('search'));

        return view('frontend.search', [
            'title' => 'Search',
            'description' => '',
            'keywords' => '',
            'main_blogs' => $results,
        ]);
    }

    public function radio(Request $request)
    {
        $results = $this->search->searchRadioShows(
            $request->input('search'),
            $request->input('show_date')
        );

        return view('frontend.search_radio', [
            'title' => 'Search',
            'description' => '',
            'keywords' => '',
            'main_blogs' => $results,
        ]);
    }
}
