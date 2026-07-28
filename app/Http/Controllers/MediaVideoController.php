<?php

namespace App\Http\Controllers;

use App\Models\MediaVideo;
use App\Services\ContentPageService;

class MediaVideoController extends Controller
{
    public function __construct(private ContentPageService $contentPages) {}

    public function posts()
    {
        return view('frontend.media_video', $this->contentPages->listingViewData('Media Video'));
    }

    public function detail(string $link)
    {
        return view(
            'frontend.media_video_detail',
            $this->contentPages->detailViewData(MediaVideo::class, $link, 'media_video_datas')
        );
    }
}
