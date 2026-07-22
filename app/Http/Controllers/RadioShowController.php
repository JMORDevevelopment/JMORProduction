<?php

namespace App\Http\Controllers;

use App\Services\ContentPageService;
use App\Services\RadioShowService;

class RadioShowController extends Controller
{
    public function __construct(
        private RadioShowService $radioShows,
        private ContentPageService $contentPages
    ) {
    }

    public function posts()
    {
        return view('frontend.jmor_radio', $this->contentPages->listingViewData('Jmor Shows'));
    }

    public function detail(string $link)
    {
        return view(
            'frontend.jmor_radio_detail',
            $this->contentPages->detailViewData('radio_show', $link, 'jmor_radio_datas')
        );
    }

    public function category(string $categoryLink, ?string $year = null)
    {
        return view(
            'frontend.category_radio_show',
            $this->radioShows->categoryViewData($categoryLink, $year)
        );
    }
}
