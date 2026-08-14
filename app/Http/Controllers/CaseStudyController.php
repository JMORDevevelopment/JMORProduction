<?php

namespace App\Http\Controllers;

use App\Models\CaseStudy;
use App\Services\ContentPageService;

class CaseStudyController extends Controller
{
    public function __construct(private ContentPageService $contentPages) {}

    public function posts()
    {
        return view('frontend.case_studies', array_merge(
            $this->contentPages->listingViewData('Case Studies'),
            [
                'posts' => CaseStudy::get(),
                'latestPosts' => CaseStudy::orderBy('id', 'desc')->limit(5)->get(),
            ]
        ));
    }

    public function detail(string $link)
    {
        return view('frontend.case_studies_detail', array_merge(
            $this->contentPages->detailViewData(CaseStudy::class, $link, 'case_studies_datas'),
            ['latestPosts' => CaseStudy::orderBy('id', 'desc')->limit(5)->get()]
        ));
    }
}
