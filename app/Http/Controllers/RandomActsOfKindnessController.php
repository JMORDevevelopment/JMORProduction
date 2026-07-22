<?php

namespace App\Http\Controllers;

use App\Services\ContentPageService;

class RandomActsOfKindnessController extends Controller
{
    public function __construct(private ContentPageService $contentPages)
    {
    }

    public function posts()
    {
        return view('frontend.random_acts_of_kindness', $this->contentPages->listingViewData('Random Acts of Kindness'));
    }

    public function detail(string $link)
    {
        return view(
            'frontend.random_acts_of_kindness_detail',
            $this->contentPages->detailViewData('random_acts_of_kindness', $link, 'random_acts_of_kindness_datas')
        );
    }
}
