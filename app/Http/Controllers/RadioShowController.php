<?php

namespace App\Http\Controllers;

use App\Models\CategoryRadioShow;
use App\Models\RadioShow;
use App\Services\ContentPageService;
use App\Services\RadioShowService;

class RadioShowController extends Controller
{
    public function __construct(
        private RadioShowService $radioShows,
        private ContentPageService $contentPages
    ) {}

    public function posts()
    {
        return view('frontend.jmor_radio', array_merge(
            $this->contentPages->listingViewData('Jmor Shows'),
            [
                'shows' => RadioShow::orderBy('id', 'desc')->get(),
                'latestShows' => RadioShow::orderBy('id', 'desc')->limit(5)->get(),
                'categories' => CategoryRadioShow::orderBy('id', 'asc')->get(),
            ]
        ));
    }

    public function detail(string $link)
    {
        return view('frontend.jmor_radio_detail', array_merge(
            $this->contentPages->detailViewData(RadioShow::class, $link, 'jmor_radio_datas'),
            [
                'latestShows' => RadioShow::orderBy('id', 'desc')->limit(5)->get(),
                'categories' => CategoryRadioShow::orderBy('id', 'asc')->get(),
            ]
        ));
    }

    public function category(string $categoryLink, ?string $year = null)
    {
        return view('frontend.category_radio_show', array_merge(
            $this->radioShows->categoryViewData($categoryLink, $year),
            ['categories' => CategoryRadioShow::orderBy('id', 'asc')->get()]
        ));
    }
}
