<?php

namespace App\Http\Controllers;

use App\Services\ContentPageService;

class PressReleaseController extends Controller
{
    public function __construct(private ContentPageService $contentPages)
    {
    }

    public function posts()
    {
        return view('frontend.press_releases', $this->contentPages->listingViewData('Press Releases'));
    }

    public function detail(string $link)
    {
        return view(
            'frontend.press_releases_detail',
            $this->contentPages->detailViewData('press_releases', $link, 'press_releases_datas')
        );
    }
}
