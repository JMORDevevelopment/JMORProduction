<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Models\Event;
=======
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
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
<<<<<<< HEAD
            $this->contentPages->detailViewData(Event::class, $link, 'events_datas')
=======
            $this->contentPages->detailViewData('events', $link, 'events_datas')
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
        );
    }
}
