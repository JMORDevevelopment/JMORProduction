<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Models\BrandGuideline;
=======
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
use App\Services\ContentPageService;

class BrandGuidelineController extends Controller
{
    public function __construct(private ContentPageService $contentPages)
    {
    }

    public function posts()
    {
        return view('frontend.brand_guidelines', $this->contentPages->listingViewData('Brand Guidelines'));
    }

    public function detail(string $link)
    {
        return view(
            'frontend.brand_guidelines_detail',
<<<<<<< HEAD
            $this->contentPages->detailViewData(BrandGuideline::class, $link, 'brand_guidelines_datas')
=======
            $this->contentPages->detailViewData('brand_guidelines', $link, 'brand_guidelines_datas')
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
        );
    }
}
