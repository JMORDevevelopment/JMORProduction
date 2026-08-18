<?php

namespace App\Http\Controllers;

use App\Models\BrandGuideline;
use App\Services\ContentPageService;

class BrandGuidelineController extends Controller
{
    public function __construct(private ContentPageService $contentPages) {}

    public function posts()
    {
        return view('frontend.brand_guidelines', array_merge(
            $this->contentPages->listingViewData('Brand Guidelines'),
            [
                'posts' => BrandGuideline::get(),
                'latestPosts' => BrandGuideline::orderBy('id', 'desc')->limit(5)->get(),
            ]
        ));
    }

    public function detail(string $link)
    {
        return view('frontend.brand_guidelines_detail', array_merge(
            $this->contentPages->detailViewData(BrandGuideline::class, $link, 'brand_guidelines_datas'),
            ['latestPosts' => BrandGuideline::orderBy('id', 'desc')->limit(5)->get()]
        ));
    }
}
