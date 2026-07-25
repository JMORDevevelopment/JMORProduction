<?php

namespace App\Http\Controllers;

use App\Services\ContentPageService;

class EventController extends Controller
{
    public function __construct(private ContentPageService $contentPages)
    {
    }

    public function posts()
    {
        return view('frontend.events', $this->contentPages->listingViewData('Events'));
    }

    public function detail(string $link)
    {
        return view(
            'frontend.events_detail',
            $this->contentPages->detailViewData('events', $link, 'events_datas')
        );
    }
}
