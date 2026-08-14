<?php

namespace App\Http\Controllers;

use App\Models\MediaVideo;
use App\Services\ContentPageService;

class MediaVideoController extends Controller
{
    public function __construct(private ContentPageService $contentPages) {}

    public function posts()
    {
        return view('frontend.media_video', array_merge(
            $this->contentPages->listingViewData('Media Video'),
            [
                'posts' => MediaVideo::get(),
                'latestPosts' => MediaVideo::orderBy('id', 'desc')->limit(5)->get(),
            ]
        ));
    }

    public function detail(string $link)
    {
        return view('frontend.media_video_detail', array_merge(
            $this->contentPages->detailViewData(MediaVideo::class, $link, 'media_video_datas'),
            ['latestPosts' => MediaVideo::orderBy('id', 'desc')->limit(5)->get()]
        ));
    }
}
