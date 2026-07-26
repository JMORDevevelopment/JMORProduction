<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Models\Recommended;
=======
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
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
<<<<<<< HEAD
            $this->contentPages->detailViewData(Recommended::class, $link, 'recommended_datas')
=======
            $this->contentPages->detailViewData('recommended', $link, 'recommended_datas')
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
        );
    }
}
