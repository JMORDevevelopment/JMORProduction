<?php

namespace App\Http\Controllers;

use App\Services\ContentPageService;

class RecommendedController extends Controller
{
    public function __construct(private ContentPageService $contentPages)
    {
    }

    public function posts()
    {
        return view('frontend.recommended', $this->contentPages->listingViewData('Recommended'));
    }

    public function detail(string $link)
    {
        return view(
            'frontend.recommended_detail',
            $this->contentPages->detailViewData('recommended', $link, 'recommended_datas')
        );
    }
}
