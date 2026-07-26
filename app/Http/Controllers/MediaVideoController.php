<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Models\MediaVideo;
=======
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
use App\Services\ContentPageService;

class MediaVideoController extends Controller
{
    public function __construct(private ContentPageService $contentPages)
    {
    }

    public function posts()
    {
        return view('frontend.media_video', $this->contentPages->listingViewData('Media Video'));
    }

    public function detail(string $link)
    {
        return view(
            'frontend.media_video_detail',
<<<<<<< HEAD
            $this->contentPages->detailViewData(MediaVideo::class, $link, 'media_video_datas')
=======
            $this->contentPages->detailViewData('media_video', $link, 'media_video_datas')
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
        );
    }
}
