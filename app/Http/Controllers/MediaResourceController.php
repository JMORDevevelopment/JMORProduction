<?php

namespace App\Http\Controllers;

use App\Models\MediaResource;
use App\Services\ContentPageService;

class MediaResourceController extends Controller
{
    public function __construct(private ContentPageService $contentPages) {}

    public function posts()
    {
        return view('frontend.media_resources', array_merge(
            $this->contentPages->listingViewData('Media Resources'),
            [
                'posts' => MediaResource::get(),
                'latestPosts' => MediaResource::orderBy('id', 'desc')->limit(5)->get(),
            ]
        ));
    }

    public function detail(string $link)
    {
        return view('frontend.media_resources_detail', array_merge(
            $this->contentPages->detailViewData(MediaResource::class, $link, 'media_resouces_datas'),
            ['latestPosts' => MediaResource::orderBy('id', 'desc')->limit(5)->get()]
        ));
    }
}
