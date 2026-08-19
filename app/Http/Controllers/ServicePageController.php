<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Service;
use App\Services\ContentPageService;

class ServicePageController extends Controller
{
    public function __construct(private ContentPageService $contentPages) {}

    public function list()
    {
        return view('frontend.service', array_merge(
            $this->contentPages->listingViewData('Services'),
            [
                'services' => Service::all(),
            ]
        ));
    }

    public function detail(string $link)
    {
        return view('frontend.service_detail', array_merge(
            $this->contentPages->detailViewData(Service::class, $link, 'service_datas'),
            ['latestPosts' => Blog::orderBy('id', 'desc')->limit(5)->get()]
        ));
    }
}
