<?php

namespace App\Http\Controllers;

use App\Models\Recommended;
use App\Services\ContentPageService;

class RecommendedController extends Controller
{
    public function __construct(private ContentPageService $contentPages) {}

    public function posts()
    {
        return view('frontend.recommended', array_merge(
            $this->contentPages->listingViewData('Recommended'),
            [
                'posts' => Recommended::orderBy('id', 'desc')->get(),
                'latestPosts' => Recommended::orderBy('id', 'desc')->limit(5)->get(),
            ]
        ));
    }

    public function detail(string $link)
    {
        return view('frontend.recommended_detail', array_merge(
            $this->contentPages->detailViewData(Recommended::class, $link, 'recommended_datas'),
            ['latestPosts' => Recommended::orderBy('id', 'desc')->limit(5)->get()]
        ));
    }
}
