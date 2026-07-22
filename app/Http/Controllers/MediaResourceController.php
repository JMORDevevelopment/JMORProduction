<?php

namespace App\Http\Controllers;

use App\Services\ContentPageService;

class MediaResourceController extends Controller
{
    public function __construct(private ContentPageService $contentPages)
    {
    }

    public function posts()
    {
        return view('frontend.media_resources', $this->contentPages->listingViewData('Media Resources'));
    }

    public function detail(string $link)
    {
        return view(
            'frontend.media_resources_detail',
            $this->contentPages->detailViewData('media_resouces', $link, 'media_resouces_datas')
        );
    }
}
