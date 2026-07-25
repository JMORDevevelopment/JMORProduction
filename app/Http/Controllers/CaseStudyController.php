<?php

namespace App\Http\Controllers;

use App\Models\CaseStudy;
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
            $this->contentPages->detailViewData(CaseStudy::class, $link, 'case_studies_datas')
        );
    }
}
