<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Models\RadioShow;
=======
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
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
<<<<<<< HEAD
            $this->contentPages->detailViewData(RadioShow::class, $link, 'jmor_radio_datas')
=======
            $this->contentPages->detailViewData('radio_show', $link, 'jmor_radio_datas')
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
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
