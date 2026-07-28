<?php

namespace App\Http\Controllers;

use App\Models\BrandGuideline;
use App\Services\ContentPageService;

class BrandGuidelineController extends Controller
{
    public function __construct(private ContentPageService $contentPages) {}

    public function posts()
    {
        return view('frontend.brand_guidelines', $this->contentPages->listingViewData('Brand Guidelines'));
    }

    public function detail(string $link)
    {
        return view(
            'frontend.brand_guidelines_detail',
            $this->contentPages->detailViewData(BrandGuideline::class, $link, 'brand_guidelines_datas')
        );
    }
}
