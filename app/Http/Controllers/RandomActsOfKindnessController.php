<?php

namespace App\Http\Controllers;

use App\Models\RandomActsOfKindness;
use App\Services\ContentPageService;

class RandomActsOfKindnessController extends Controller
{
    public function __construct(private ContentPageService $contentPages) {}

    public function posts()
    {
        return view('frontend.random_acts_of_kindness', array_merge(
            $this->contentPages->listingViewData('Random Acts of Kindness'),
            [
                'posts' => RandomActsOfKindness::get(),
                'latestPosts' => RandomActsOfKindness::orderBy('id', 'desc')->limit(5)->get(),
            ]
        ));
    }

    public function detail(string $link)
    {
        return view('frontend.random_acts_of_kindness_detail', array_merge(
            $this->contentPages->detailViewData(RandomActsOfKindness::class, $link, 'random_acts_of_kindness_datas'),
            ['latestPosts' => RandomActsOfKindness::orderBy('id', 'desc')->limit(5)->get()]
        ));
    }
}
