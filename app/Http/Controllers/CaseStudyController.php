<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Models\CaseStudy;
=======
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
use App\Services\ContentPageService;

class CaseStudyController extends Controller
{
    public function __construct(private ContentPageService $contentPages)
    {
    }

    public function posts()
    {
        return view('frontend.case_studies', $this->contentPages->listingViewData('Case Studies'));
    }

    public function detail(string $link)
    {
        return view(
            'frontend.case_studies_detail',
<<<<<<< HEAD
            $this->contentPages->detailViewData(CaseStudy::class, $link, 'case_studies_datas')
=======
            $this->contentPages->detailViewData('case_studies', $link, 'case_studies_datas')
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
        );
    }
}
