<?php

namespace App\Http\Controllers;

use App\Services\ContentPageService;

class ServicePageController extends Controller
{
    public function __construct(private ContentPageService $contentPages)
    {
    }

    public function list()
    {
        return view('frontend.service', $this->contentPages->listingViewData('Services'));
    }

    public function detail(string $link)
    {
        return view(
            'frontend.service_detail',
            $this->contentPages->detailViewData('service', $link, 'service_datas')
        );
    }
}
