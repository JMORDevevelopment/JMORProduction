<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Services\ContentPageService;

class EventController extends Controller
{
    public function __construct(private ContentPageService $contentPages) {}

    public function posts()
    {
        return view('frontend.events', array_merge(
            $this->contentPages->listingViewData('Events'),
            [
                'posts' => Event::orderBy('id', 'desc')->get(),
                'latestPosts' => Event::orderBy('id', 'desc')->limit(5)->get(),
            ]
        ));
    }

    public function detail(string $link)
    {
        return view('frontend.events_detail', array_merge(
            $this->contentPages->detailViewData(Event::class, $link, 'events_datas'),
            ['latestPosts' => Event::orderBy('id', 'desc')->limit(5)->get()]
        ));
    }
}
